<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

// Page configuration
$pageTitle = 'Policies - Softpro Insurance Management System';
$useNavbar = true;
$useSidebar = false;

$pageHeader = [
    'title' => 'Policy Management',
    'icon' => '<i class="fas fa-file-alt"></i>',
    'description' => 'Manage all insurance policies, renewals, and client information',
    'actions' => '<button class="btn btn-primary" onclick="showAddPolicyModal()">
                    <i class="fas fa-plus me-1"></i>Add New Policy
                  </button>
                  <button class="btn btn-success" onclick="generateReport(\'quick\')">
                    <i class="fas fa-chart-line me-1"></i>Quick Report
                  </button>'
];

$breadcrumb = [
    ['name' => 'Policies']
];

include 'include/header-modern.php';
?>

<!-- Main Content -->
<div class="row">
    <div class="col-12">
        <!-- Policies Statistics Cards -->
        <div class="row mb-4">
            <?php
            // Get statistics
            $totalPolicies = mysqli_num_rows(mysqli_query($con, "SELECT * FROM policy"));
            $activePolicies = mysqli_num_rows(mysqli_query($con, "SELECT * FROM policy WHERE policy_end_date >= CURDATE()"));
            $expiredPolicies = mysqli_num_rows(mysqli_query($con, "SELECT * FROM policy WHERE policy_end_date < CURDATE()"));
            $renewalsDue = mysqli_num_rows(mysqli_query($con, "SELECT * FROM policy WHERE policy_end_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND policy_end_date >= CURDATE()"));
            
            // Get this month's revenue
            $revenueQuery = mysqli_query($con, "SELECT SUM(premium_amount) as total, SUM(commission_amount) as commission FROM policy WHERE DATE_FORMAT(policy_start_date, '%Y-%m') = '" . date('Y-m') . "'");
            $revenueData = mysqli_fetch_array($revenueQuery);
            ?>
            
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-primary d-flex align-items-center justify-content-center">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Total Policies</h6>
                                <h4 class="mb-0"><?php echo number_format($totalPolicies); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-success d-flex align-items-center justify-content-center">
                                    <i class="fas fa-check-circle text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Active Policies</h6>
                                <h4 class="mb-0"><?php echo number_format($activePolicies); ?></h4>
                                <small class="text-success">
                                    <?php echo round(($activePolicies / max($totalPolicies, 1)) * 100, 1); ?>% of total
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-warning d-flex align-items-center justify-content-center">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Renewals Due</h6>
                                <h4 class="mb-0 text-warning"><?php echo number_format($renewalsDue); ?></h4>
                                <small class="text-muted">Next 30 days</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-info d-flex align-items-center justify-content-center">
                                    <i class="fas fa-rupee-sign text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">This Month Revenue</h6>
                                <h4 class="mb-0 text-info">₹<?php echo number_format($revenueData['total'] ?? 0); ?></h4>
                                <small class="text-muted">
                                    Commission: ₹<?php echo number_format($revenueData['commission'] ?? 0); ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Policies DataTable Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2 text-primary"></i>All Policies
                    </h5>
                    <div class="d-flex gap-2">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="filterTable('active')">Active Policies</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterTable('expired')">Expired Policies</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterTable('renewal')">Due for Renewal</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="filterTable('')">Show All</a></li>
                            </ul>
                        </div>
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshTable()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="policiesTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Client Name</th>
                                <th>Mobile</th>
                                <th>Vehicle Number</th>
                                <th>Insurance Company</th>
                                <th>Policy Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Premium</th>
                                <th>Status</th>
                                <th class="no-print">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom JavaScript for this page -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    window.table = initializeDataTable('#policiesTable', {
        ajax: {
            url: 'api/policies_data.php',
            type: 'POST',
            error: function(xhr, error, thrown) {
                console.error('DataTable AJAX Error:', error);
                showErrorToast('Failed to load policies data. Please refresh the page.');
            }
        },
        columns: [
            { data: 'serial', orderable: false, searchable: false },
            { data: 'client_name' },
            { data: 'mobile_number' },
            { data: 'vehicle_number' },
            { data: 'insurance_company' },
            { data: 'policy_type' },
            { data: 'policy_start_date' },
            { data: 'policy_end_date' },
            { data: 'premium_amount' },
            { data: 'status' },
            { data: 'actions', orderable: false, searchable: false, className: 'no-print' }
        ],
        order: [[6, 'desc']], // Sort by start date descending
        pageLength: 25,
        responsive: true
    });
    
    // Auto-refresh every 5 minutes
    setInterval(function() {
        if (window.table) {
            window.table.ajax.reload(null, false);
        }
    }, 300000);
});

// Filter table function
function filterTable(filter) {
    if (!window.table) return;
    
    let searchTerm = '';
    switch(filter) {
        case 'active':
            searchTerm = 'Active';
            break;
        case 'expired':
            searchTerm = 'Expired';
            break;
        case 'renewal':
            searchTerm = 'Renewal';
            break;
        default:
            searchTerm = '';
    }
    
    window.table.columns(9).search(searchTerm).draw(); // Search in status column
    showInfoToast(`Filtered to show ${filter || 'all'} policies`);
}

// Refresh table function
function refreshTable() {
    if (window.table) {
        window.table.ajax.reload();
        showSuccessToast('Table refreshed successfully');
    }
}

// Policy action functions
function editPolicy(policyId) {
    showEditPolicyModal(policyId);
}

function viewPolicy(policyId) {
    showViewPolicyModal(policyId);
}

function deletePolicy(policyId, vehicleNumber) {
    showDeletePolicyModal(policyId, vehicleNumber);
}

function renewPolicy(policyId) {
    if (confirm('Are you sure you want to mark this policy for renewal?')) {
        fetch('api/mark_renewal.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ policy_id: policyId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessToast('Policy marked for renewal');
                window.table.ajax.reload();
            } else {
                showErrorToast(data.message || 'Failed to mark policy for renewal');
            }
        })
        .catch(error => {
            showErrorToast('Error processing renewal request');
            console.error('Error:', error);
        });
    }
}

// Bulk operations
function initiateBulkRenewal() {
    if (confirm('This will send renewal reminders to all policies due in the next 30 days. Continue?')) {
        ModalSystem.showLoading('Sending renewal reminders...');
        
        fetch('api/bulk_renewal_reminder.php', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            ModalSystem.hideLoading();
            if (data.success) {
                showSuccessToast(`Renewal reminders sent to ${data.count} clients`);
            } else {
                showErrorToast(data.message || 'Failed to send renewal reminders');
            }
        })
        .catch(error => {
            ModalSystem.hideLoading();
            showErrorToast('Error sending renewal reminders');
            console.error('Error:', error);
        });
    }
}

// Export policies to Excel
function exportPolicies() {
    const url = 'api/export_policies.php?format=excel';
    window.open(url, '_blank');
    showInfoToast('Generating Excel export...');
}
</script>

<!-- Add custom CSS for avatar styling -->
<style>
.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}

.card:hover {
    transform: translateY(-2px);
}

.table th {
    font-weight: 600;
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.badge {
    font-size: 0.75rem;
}

/* Status badge colors */
.status-active {
    background-color: #198754;
}

.status-expired {
    background-color: #dc3545;
}

.status-renewal {
    background-color: #fd7e14;
}

.status-urgent {
    background-color: #dc3545;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}
</style>

<?php
$customJS = "
<script>
// Page-specific initialization
document.addEventListener('DOMContentLoaded', function() {
    // Show welcome message
    showInfoToast('Policies loaded successfully');
    
    // Initialize tooltips for action buttons
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle=\"tooltip\"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
";

include 'include/footer-modern.php';
?>

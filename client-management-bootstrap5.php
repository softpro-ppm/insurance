<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

// Page configuration
$pageTitle = 'Client Management - Softpro Insurance Management System';
$useNavbar = true;
$useSidebar = false;

$pageHeader = [
    'title' => 'Client Management',
    'icon' => '<i class="fas fa-users"></i>',
    'description' => 'Manage client information, policies, and communication',
    'actions' => '<button class="btn btn-primary" onclick="showAddClientModal()">
                    <i class="fas fa-user-plus me-1"></i>Add New Client
                  </button>
                  <div class="dropdown d-inline-block ms-2">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="exportClients(\'excel\')">Excel Export</a></li>
                        <li><a class="dropdown-item" href="#" onclick="exportClients(\'pdf\')">PDF Export</a></li>
                        <li><a class="dropdown-item" href="#" onclick="exportClients(\'contacts\')">Contact List</a></li>
                    </ul>
                  </div>'
];

$breadcrumb = [
    ['name' => 'Client Management']
];

include 'include/header-modern.php';

// Get client statistics
$totalClients = mysqli_num_rows(mysqli_query($con, "SELECT DISTINCT client_name, mobile_number FROM policy"));
$activeClients = mysqli_num_rows(mysqli_query($con, "SELECT DISTINCT client_name, mobile_number FROM policy WHERE policy_end_date >= CURDATE()"));
$newThisMonth = mysqli_num_rows(mysqli_query($con, "SELECT DISTINCT client_name, mobile_number FROM policy WHERE DATE_FORMAT(policy_start_date, '%Y-%m') = '" . date('Y-m') . "'"));
$renewalClients = mysqli_num_rows(mysqli_query($con, "SELECT DISTINCT client_name, mobile_number FROM policy WHERE policy_end_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND policy_end_date >= CURDATE()"));
?>

<!-- Client Management Content -->
<div class="row">
    <!-- Client Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                                <i class="fas fa-users fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Clients</h6>
                            <h3 class="mb-0"><?php echo number_format($totalClients); ?></h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>All time
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
                            <div class="avatar-lg rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center">
                                <i class="fas fa-user-check fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Active Clients</h6>
                            <h3 class="mb-0"><?php echo number_format($activeClients); ?></h3>
                            <small class="text-muted">
                                <?php echo round(($activeClients / max($totalClients, 1)) * 100, 1); ?>% of total
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
                            <div class="avatar-lg rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center">
                                <i class="fas fa-user-plus fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">New This Month</h6>
                            <h3 class="mb-0 text-info"><?php echo number_format($newThisMonth); ?></h3>
                            <small class="text-muted">
                                <?php echo date('F Y'); ?>
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
                            <div class="avatar-lg rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center">
                                <i class="fas fa-bell fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Renewal Due</h6>
                            <h3 class="mb-0 text-warning"><?php echo number_format($renewalClients); ?></h3>
                            <small class="text-muted">Next 30 days</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Client Management Tools -->
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tools me-2 text-primary"></i>Client Management Tools
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="d-grid">
                            <button class="btn btn-outline-primary" onclick="bulkCommunication()">
                                <i class="fas fa-comments me-2"></i>
                                Bulk Communication
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-grid">
                            <button class="btn btn-outline-success" onclick="renewalReminders()">
                                <i class="fas fa-bell me-2"></i>
                                Send Renewal Reminders
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-grid">
                            <button class="btn btn-outline-info" onclick="clientAnalytics()">
                                <i class="fas fa-chart-pie me-2"></i>
                                Client Analytics
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Clients DataTable -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2 text-primary"></i>All Clients
                    </h5>
                    <div class="d-flex gap-2">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="filterClients('active')">Active Clients</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterClients('renewal')">Renewal Due</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterClients('new')">New This Month</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="filterClients('')">Show All</a></li>
                            </ul>
                        </div>
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshClientsTable()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="clientsTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Client Name</th>
                                <th>Mobile Number</th>
                                <th>Email</th>
                                <th>Total Policies</th>
                                <th>Active Policies</th>
                                <th>Total Premium</th>
                                <th>Last Policy Date</th>
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

<!-- Client-specific modals -->
<!-- Add Client Modal -->
<div class="modal fade" id="addClientModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Add New Client Policy
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addClientForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Client Information -->
                        <div class="col-12">
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-user me-2"></i>Client Information
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Client Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="client_name" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" name="mobile_number" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address">
                        </div>
                        
                        <!-- Vehicle Information -->
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-car me-2"></i>Vehicle Information
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Vehicle Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="vehicle_number" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="vehicle_type" required>
                                <option value="">Select Vehicle Type</option>
                                <option value="Car">Car</option>
                                <option value="Motorcycle">Motorcycle</option>
                                <option value="Truck">Truck</option>
                                <option value="Bus">Bus</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <!-- Policy Information -->
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-file-contract me-2"></i>Policy Information
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Insurance Company <span class="text-danger">*</span></label>
                            <select class="form-select" name="insurance_company" required>
                                <option value="">Select Insurance Company</option>
                                <option value="ICICI Lombard">ICICI Lombard</option>
                                <option value="Bajaj Allianz">Bajaj Allianz</option>
                                <option value="HDFC Ergo">HDFC Ergo</option>
                                <option value="TATA AIG">TATA AIG</option>
                                <option value="New India Assurance">New India Assurance</option>
                                <option value="Oriental Insurance">Oriental Insurance</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Policy Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="policy_type" required>
                                <option value="">Select Policy Type</option>
                                <option value="Comprehensive">Comprehensive</option>
                                <option value="Third Party">Third Party</option>
                                <option value="Own Damage">Own Damage</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Policy Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="policy_start_date" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Policy End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="policy_end_date" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Premium Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="premium_amount" step="0.01" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Commission Amount</label>
                            <input type="number" class="form-control" name="commission_amount" step="0.01">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save Client Policy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Client Details Modal -->
<div class="modal fade" id="clientDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user me-2"></i>Client Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="clientDetailsContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
                <button type="button" class="btn btn-primary" onclick="addNewPolicyForClient()">
                    <i class="fas fa-plus me-1"></i>Add New Policy
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Custom JavaScript for Client Management -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Clients DataTable
    window.clientsTable = initializeDataTable('#clientsTable', {
        ajax: {
            url: 'api/clients_data.php',
            type: 'POST',
            error: function(xhr, error, thrown) {
                console.error('DataTable AJAX Error:', error);
                showErrorToast('Failed to load clients data. Please refresh the page.');
            }
        },
        columns: [
            { data: 'serial', orderable: false, searchable: false },
            { data: 'client_name' },
            { data: 'mobile_number' },
            { data: 'email' },
            { data: 'total_policies' },
            { data: 'active_policies' },
            { data: 'total_premium' },
            { data: 'last_policy_date' },
            { data: 'status' },
            { data: 'actions', orderable: false, searchable: false, className: 'no-print' }
        ],
        order: [[7, 'desc']], // Sort by last policy date descending
        pageLength: 25,
        responsive: true
    });
    
    // Auto-refresh every 5 minutes
    setInterval(function() {
        if (window.clientsTable) {
            window.clientsTable.ajax.reload(null, false);
        }
    }, 300000);
});

// Client Management Functions
function showAddClientModal() {
    ModalSystem.clearForm('addClientForm');
    const modal = new bootstrap.Modal(document.getElementById('addClientModal'));
    modal.show();
}

function showClientDetails(clientName, mobileNumber) {
    ModalSystem.showLoading('Loading client details...');
    
    fetch(`api/get_client_details.php?name=${encodeURIComponent(clientName)}&mobile=${encodeURIComponent(mobileNumber)}`)
        .then(response => response.text())
        .then(html => {
            ModalSystem.hideLoading();
            document.getElementById('clientDetailsContent').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('clientDetailsModal'));
            modal.show();
        })
        .catch(error => {
            ModalSystem.hideLoading();
            showErrorToast('Error loading client details');
            console.error('Error:', error);
        });
}

function editClientPolicy(policyId) {
    showEditPolicyModal(policyId);
}

function deleteClientPolicy(policyId, vehicleNumber) {
    showDeletePolicyModal(policyId, vehicleNumber);
}

// Filter functions
function filterClients(filter) {
    if (!window.clientsTable) return;
    
    let searchTerm = '';
    switch(filter) {
        case 'active':
            searchTerm = 'Active';
            break;
        case 'renewal':
            searchTerm = 'Renewal';
            break;
        case 'new':
            // Filter by current month
            const currentMonth = new Date().toISOString().substr(0, 7);
            searchTerm = currentMonth;
            break;
        default:
            searchTerm = '';
    }
    
    window.clientsTable.columns(8).search(searchTerm).draw(); // Search in status column
    showInfoToast(`Filtered to show ${filter || 'all'} clients`);
}

function refreshClientsTable() {
    if (window.clientsTable) {
        window.clientsTable.ajax.reload();
        showSuccessToast('Client table refreshed successfully');
    }
}

// Export functions
function exportClients(format) {
    let url = '';
    switch(format) {
        case 'excel':
            url = 'api/export_clients.php?format=excel';
            break;
        case 'pdf':
            url = 'api/export_clients.php?format=pdf';
            break;
        case 'contacts':
            url = 'api/export_clients.php?format=contacts';
            break;
    }
    
    if (url) {
        window.open(url, '_blank');
        showInfoToast(`Generating ${format.toUpperCase()} export...`);
    }
}

// Communication functions
function bulkCommunication() {
    showInfoToast('Bulk communication feature coming soon!');
}

function renewalReminders() {
    if (confirm('Send renewal reminders to all clients with policies expiring in the next 30 days?')) {
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

function clientAnalytics() {
    showInfoToast('Client analytics dashboard coming soon!');
}

function addNewPolicyForClient() {
    bootstrap.Modal.getInstance(document.getElementById('clientDetailsModal')).hide();
    showAddPolicyModal();
}

// Form submission handler
document.addEventListener('DOMContentLoaded', function() {
    const addClientForm = document.getElementById('addClientForm');
    if (addClientForm) {
        addClientForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'add');
            
            ModalSystem.showLoading('Adding client policy...');
            
            fetch('api/client_operations.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                ModalSystem.hideLoading();
                if (data.success) {
                    showSuccessToast('Client policy added successfully');
                    bootstrap.Modal.getInstance(document.getElementById('addClientModal')).hide();
                    ModalSystem.clearForm('addClientForm');
                    
                    // Refresh tables
                    if (window.clientsTable) {
                        window.clientsTable.ajax.reload();
                    }
                    if (window.table) {
                        window.table.ajax.reload();
                    }
                } else {
                    showErrorToast(data.message || 'Failed to add client policy');
                }
            })
            .catch(error => {
                ModalSystem.hideLoading();
                showErrorToast('Error adding client policy');
                console.error('Error:', error);
            });
        });
    }
});
</script>

<!-- Custom CSS for Client Management -->
<style>
.avatar-lg {
    width: 4rem;
    height: 4rem;
}

.client-status-active {
    background-color: #198754;
}

.client-status-renewal {
    background-color: #fd7e14;
}

.client-status-inactive {
    background-color: #6c757d;
}

.client-card {
    transition: transform 0.2s ease;
}

.client-card:hover {
    transform: translateY(-2px);
}

.client-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 1.2rem;
}
</style>

<?php include 'include/footer-modern.php'; ?>

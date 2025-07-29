<?php
session_start();
require_once '../connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$clientName = isset($_GET['name']) ? urldecode($_GET['name']) : '';
$mobileNumber = isset($_GET['mobile']) ? urldecode($_GET['mobile']) : '';

if (empty($clientName) || empty($mobileNumber)) {
    echo "<div class='alert alert-danger'>
            <i class='fas fa-exclamation-triangle me-2'></i>
            Client name and mobile number are required.
          </div>";
    exit;
}

try {
    // Get client policies
    $stmt = mysqli_prepare($con, "
        SELECT * FROM policy 
        WHERE client_name = ? AND mobile_number = ? 
        ORDER BY policy_start_date DESC
    ");
    mysqli_stmt_bind_param($stmt, 'ss', $clientName, $mobileNumber);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $policies = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $policies[] = $row;
    }
    
    if (empty($policies)) {
        echo "<div class='alert alert-warning'>
                <i class='fas fa-info-circle me-2'></i>
                No policies found for this client.
              </div>";
        exit;
    }
    
    // Calculate summary statistics
    $totalPolicies = count($policies);
    $activePolicies = 0;
    $totalPremium = 0;
    $totalCommission = 0;
    $renewalDue = 0;
    
    foreach ($policies as $policy) {
        $totalPremium += floatval($policy['premium_amount']);
        $totalCommission += floatval($policy['commission_amount']);
        
        if (strtotime($policy['policy_end_date']) >= time()) {
            $activePolicies++;
        }
        
        if (strtotime($policy['policy_end_date']) >= time() && 
            strtotime($policy['policy_end_date']) <= strtotime('+30 days')) {
            $renewalDue++;
        }
    }
    
    $latestPolicy = $policies[0];
    $email = $latestPolicy['email'] ?? 'N/A';
    $address = $latestPolicy['address'] ?? 'N/A';
    
?>

<!-- Client Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 bg-gradient-primary text-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar-xxl rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user fa-3x text-white"></i>
                        </div>
                    </div>
                    <div class="col">
                        <h3 class="text-white mb-1"><?php echo htmlspecialchars($clientName); ?></h3>
                        <div class="row g-0">
                            <div class="col-auto">
                                <i class="fas fa-phone me-2"></i>
                                <a href="tel:<?php echo htmlspecialchars($mobileNumber); ?>" class="text-white text-decoration-none">
                                    <?php echo htmlspecialchars($mobileNumber); ?>
                                </a>
                            </div>
                            <?php if ($email !== 'N/A'): ?>
                            <div class="col-auto ms-4">
                                <i class="fas fa-envelope me-2"></i>
                                <a href="mailto:<?php echo htmlspecialchars($email); ?>" class="text-white text-decoration-none">
                                    <?php echo htmlspecialchars($email); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($address !== 'N/A'): ?>
                        <div class="mt-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <span><?php echo htmlspecialchars($address); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Client Statistics -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="fas fa-file-contract fa-2x text-primary"></i>
                </div>
                <h4 class="mb-1"><?php echo $totalPolicies; ?></h4>
                <small class="text-muted">Total Policies</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="fas fa-check-circle fa-2x text-success"></i>
                </div>
                <h4 class="mb-1 text-success"><?php echo $activePolicies; ?></h4>
                <small class="text-muted">Active Policies</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="fas fa-rupee-sign fa-2x text-info"></i>
                </div>
                <h4 class="mb-1 text-info">₹<?php echo number_format($totalPremium, 2); ?></h4>
                <small class="text-muted">Total Premium</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="fas fa-bell fa-2x <?php echo $renewalDue > 0 ? 'text-warning' : 'text-secondary'; ?>"></i>
                </div>
                <h4 class="mb-1 <?php echo $renewalDue > 0 ? 'text-warning' : 'text-secondary'; ?>"><?php echo $renewalDue; ?></h4>
                <small class="text-muted">Renewal Due</small>
            </div>
        </div>
    </div>
</div>

<!-- Client Policies -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2 text-primary"></i>Policy History
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="exportClientPolicies('<?php echo urlencode($clientName); ?>', '<?php echo urlencode($mobileNumber); ?>')">
                            <i class="fas fa-download me-1"></i>Export
                        </button>
                        <button class="btn btn-primary btn-sm" onclick="addPolicyForClient('<?php echo urlencode($clientName); ?>', '<?php echo urlencode($mobileNumber); ?>')">
                            <i class="fas fa-plus me-1"></i>Add Policy
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Policy Details</th>
                                <th>Vehicle</th>
                                <th>Period</th>
                                <th>Premium</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($policies as $index => $policy): 
                                $isActive = strtotime($policy['policy_end_date']) >= time();
                                $isNearExpiry = strtotime($policy['policy_end_date']) <= strtotime('+30 days') && $isActive;
                                $statusClass = $isActive ? ($isNearExpiry ? 'warning' : 'success') : 'secondary';
                                $statusText = $isActive ? ($isNearExpiry ? 'Expiring Soon' : 'Active') : 'Expired';
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="bg-primary bg-opacity-10 rounded p-2">
                                                <i class="fas fa-shield-alt text-primary"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-bold"><?php echo htmlspecialchars($policy['insurance_company']); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars($policy['policy_type']); ?></small>
                                            <?php if (!empty($policy['policy_number'])): ?>
                                            <br><small class="text-primary">Policy: <?php echo htmlspecialchars($policy['policy_number']); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($policy['vehicle_number']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($policy['vehicle_type']); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="small">
                                            <i class="fas fa-calendar-start me-1 text-success"></i>
                                            <?php echo date('d M Y', strtotime($policy['policy_start_date'])); ?>
                                        </div>
                                        <div class="small">
                                            <i class="fas fa-calendar-times me-1 text-danger"></i>
                                            <?php echo date('d M Y', strtotime($policy['policy_end_date'])); ?>
                                        </div>
                                        <div class="small text-muted">
                                            <?php 
                                            $days = ceil((strtotime($policy['policy_end_date']) - time()) / (60 * 60 * 24));
                                            if ($days > 0) {
                                                echo $days . ' days remaining';
                                            } else {
                                                echo abs($days) . ' days ago';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-bold text-success">₹<?php echo number_format($policy['premium_amount'], 2); ?></div>
                                        <?php if (!empty($policy['commission_amount'])): ?>
                                        <small class="text-muted">Commission: ₹<?php echo number_format($policy['commission_amount'], 2); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                    <?php if ($isNearExpiry): ?>
                                    <br><small class="text-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Renewal needed
                                    </small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary" 
                                                onclick="viewPolicyDetails(<?php echo $policy['id']; ?>)" 
                                                title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" 
                                                onclick="editPolicy(<?php echo $policy['id']; ?>)" 
                                                title="Edit Policy">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php if ($isNearExpiry): ?>
                                        <button type="button" class="btn btn-outline-warning" 
                                                onclick="renewPolicy(<?php echo $policy['id']; ?>)" 
                                                title="Renew Policy">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                        <?php endif; ?>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" 
                                                    data-bs-toggle="dropdown" title="More Actions">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="generatePolicyDocument(<?php echo $policy['id']; ?>)">
                                                    <i class="fas fa-file-pdf me-2"></i>Generate Certificate
                                                </a></li>
                                                <li><a class="dropdown-item" href="#" onclick="sendPolicyReminder(<?php echo $policy['id']; ?>)">
                                                    <i class="fas fa-sms me-2"></i>Send Reminder
                                                </a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="deletePolicy(<?php echo $policy['id']; ?>, '<?php echo htmlspecialchars($policy['vehicle_number']); ?>')">
                                                    <i class="fas fa-trash me-2"></i>Delete Policy
                                                </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Communication History -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0">
                    <i class="fas fa-comments me-2 text-primary"></i>Communication History
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center py-4">
                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No communication history available.</p>
                    <button class="btn btn-outline-primary" onclick="addCommunicationRecord('<?php echo urlencode($clientName); ?>', '<?php echo urlencode($mobileNumber); ?>')">
                        <i class="fas fa-plus me-1"></i>Add Communication
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-xxl {
    width: 5rem;
    height: 5rem;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card-hover {
    transition: transform 0.2s ease;
}

.card-hover:hover {
    transform: translateY(-2px);
}
</style>

<script>
// Policy-related functions
function viewPolicyDetails(policyId) {
    showPolicyDetailsModal(policyId);
}

function editPolicy(policyId) {
    showEditPolicyModal(policyId);
}

function renewPolicy(policyId) {
    if (confirm('Create a renewal policy based on this existing policy?')) {
        showRenewPolicyModal(policyId);
    }
}

function deletePolicy(policyId, vehicleNumber) {
    showDeletePolicyModal(policyId, vehicleNumber);
}

function generatePolicyDocument(policyId) {
    window.open(`api/generate_policy_document.php?id=${policyId}`, '_blank');
    showInfoToast('Generating policy document...');
}

function sendPolicyReminder(policyId) {
    if (confirm('Send renewal reminder to this client?')) {
        fetch(`api/send_policy_reminder.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ policy_id: policyId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessToast('Reminder sent successfully');
            } else {
                showErrorToast(data.message || 'Failed to send reminder');
            }
        })
        .catch(error => {
            showErrorToast('Error sending reminder');
            console.error('Error:', error);
        });
    }
}

// Client management functions
function addPolicyForClient(clientName, mobileNumber) {
    // Pre-fill the add policy form with client details
    showAddPolicyModal();
    
    // Wait for modal to be shown, then populate fields
    setTimeout(() => {
        const form = document.getElementById('addPolicyForm');
        if (form) {
            form.client_name.value = decodeURIComponent(clientName);
            form.mobile_number.value = decodeURIComponent(mobileNumber);
        }
    }, 500);
}

function exportClientPolicies(clientName, mobileNumber) {
    const url = `api/export_client_policies.php?name=${clientName}&mobile=${mobileNumber}`;
    window.open(url, '_blank');
    showInfoToast('Generating client policy export...');
}

function addCommunicationRecord(clientName, mobileNumber) {
    showInfoToast('Communication history feature coming soon!');
}
</script>

<?php

} catch (Exception $e) {
    echo "<div class='alert alert-danger'>
            <i class='fas fa-exclamation-triangle me-2'></i>
            Error loading client details: " . htmlspecialchars($e->getMessage()) . "
          </div>";
}
?>

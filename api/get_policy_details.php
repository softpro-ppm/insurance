<?php
session_start();
require_once '../connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$policyId = $_GET['id'] ?? 0;

if (!$policyId) {
    echo '<div class="alert alert-danger">Policy ID is required</div>';
    exit;
}

try {
    $query = "SELECT * FROM policy WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $policyId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($policy = mysqli_fetch_assoc($result)) {
        // Calculate days until expiry
        $daysUntilExpiry = (strtotime($policy['policy_end_date']) - strtotime(date('Y-m-d'))) / (60 * 60 * 24);
        
        echo '<div class="row g-3">';
        
        // Client Information
        echo '<div class="col-12">';
        echo '<h6 class="fw-bold text-primary border-bottom pb-2 mb-3">';
        echo '<i class="fas fa-user me-2"></i>Client Information';
        echo '</h6>';
        echo '</div>';
        
        echo '<div class="col-md-6">';
        echo '<strong>Client Name:</strong><br>' . htmlspecialchars($policy['client_name']);
        echo '</div>';
        
        echo '<div class="col-md-6">';
        echo '<strong>Mobile Number:</strong><br>' . htmlspecialchars($policy['mobile_number']);
        echo '</div>';
        
        if (!empty($policy['email'])) {
            echo '<div class="col-md-6">';
            echo '<strong>Email:</strong><br>' . htmlspecialchars($policy['email']);
            echo '</div>';
        }
        
        if (!empty($policy['address'])) {
            echo '<div class="col-md-6">';
            echo '<strong>Address:</strong><br>' . htmlspecialchars($policy['address']);
            echo '</div>';
        }
        
        // Vehicle Information
        echo '<div class="col-12 mt-4">';
        echo '<h6 class="fw-bold text-primary border-bottom pb-2 mb-3">';
        echo '<i class="fas fa-car me-2"></i>Vehicle Information';
        echo '</h6>';
        echo '</div>';
        
        echo '<div class="col-md-6">';
        echo '<strong>Vehicle Number:</strong><br>' . htmlspecialchars($policy['vehicle_number']);
        echo '</div>';
        
        echo '<div class="col-md-6">';
        echo '<strong>Vehicle Type:</strong><br>' . htmlspecialchars($policy['vehicle_type']);
        echo '</div>';
        
        if (!empty($policy['vehicle_brand'])) {
            echo '<div class="col-md-6">';
            echo '<strong>Brand/Make:</strong><br>' . htmlspecialchars($policy['vehicle_brand']);
            echo '</div>';
        }
        
        if (!empty($policy['vehicle_model'])) {
            echo '<div class="col-md-6">';
            echo '<strong>Model:</strong><br>' . htmlspecialchars($policy['vehicle_model']);
            echo '</div>';
        }
        
        // Policy Information
        echo '<div class="col-12 mt-4">';
        echo '<h6 class="fw-bold text-primary border-bottom pb-2 mb-3">';
        echo '<i class="fas fa-file-contract me-2"></i>Policy Information';
        echo '</h6>';
        echo '</div>';
        
        echo '<div class="col-md-6">';
        echo '<strong>Insurance Company:</strong><br>' . htmlspecialchars($policy['insurance_company']);
        echo '</div>';
        
        echo '<div class="col-md-6">';
        echo '<strong>Policy Type:</strong><br>' . htmlspecialchars($policy['policy_type']);
        echo '</div>';
        
        echo '<div class="col-md-6">';
        echo '<strong>Policy Start Date:</strong><br>' . date('F j, Y', strtotime($policy['policy_start_date']));
        echo '</div>';
        
        echo '<div class="col-md-6">';
        echo '<strong>Policy End Date:</strong><br>';
        echo '<span class="' . ($daysUntilExpiry <= 30 && $daysUntilExpiry >= 0 ? 'text-warning' : ($daysUntilExpiry < 0 ? 'text-danger' : 'text-success')) . '">';
        echo date('F j, Y', strtotime($policy['policy_end_date']));
        
        if ($daysUntilExpiry >= 0) {
            echo ' (' . ceil($daysUntilExpiry) . ' days remaining)';
        } else {
            echo ' (Expired ' . abs(ceil($daysUntilExpiry)) . ' days ago)';
        }
        echo '</span>';
        echo '</div>';
        
        echo '<div class="col-md-6">';
        echo '<strong>Premium Amount:</strong><br>₹' . number_format($policy['premium_amount'], 2);
        echo '</div>';
        
        if (!empty($policy['commission_amount'])) {
            echo '<div class="col-md-6">';
            echo '<strong>Commission Amount:</strong><br>₹' . number_format($policy['commission_amount'], 2);
            echo '</div>';
        }
        
        // Status Badge
        echo '<div class="col-12 mt-3">';
        echo '<strong>Status:</strong><br>';
        if ($daysUntilExpiry < 0) {
            echo '<span class="badge bg-danger fs-6">Expired</span>';
        } elseif ($daysUntilExpiry <= 7) {
            echo '<span class="badge bg-warning fs-6">Urgent Renewal</span>';
        } elseif ($daysUntilExpiry <= 30) {
            echo '<span class="badge bg-info fs-6">Renewal Due Soon</span>';
        } else {
            echo '<span class="badge bg-success fs-6">Active</span>';
        }
        echo '</div>';
        
        // Documents
        if (!empty($policy['policy_document'])) {
            echo '<div class="col-12 mt-4">';
            echo '<h6 class="fw-bold text-primary border-bottom pb-2 mb-3">';
            echo '<i class="fas fa-paperclip me-2"></i>Documents';
            echo '</h6>';
            echo '<a href="uploads/' . htmlspecialchars($policy['policy_document']) . '" target="_blank" class="btn btn-outline-primary btn-sm">';
            echo '<i class="fas fa-file-pdf me-1"></i>View Policy Document';
            echo '</a>';
            echo '</div>';
        }
        
        echo '</div>';
        
    } else {
        echo '<div class="alert alert-danger">';
        echo '<i class="fas fa-exclamation-triangle me-2"></i>Policy not found';
        echo '</div>';
    }
    
} catch (Exception $e) {
    echo '<div class="alert alert-danger">';
    echo '<i class="fas fa-exclamation-triangle me-2"></i>Database error: ' . htmlspecialchars($e->getMessage());
    echo '</div>';
}

mysqli_close($con);
?>

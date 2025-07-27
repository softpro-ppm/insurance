<?php
require 'session.php';
require 'config.php';

// Set JSON header
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors in JSON response

// Log all requests for debugging
error_log("Policy operations request: " . print_r($_POST, true));

try {
    // Check if database connection is available
    if (!isset($con) || !$con) {
        throw new Exception('Database connection not available');
    }
    
    $action = $_POST['action'] ?? '';
    
    if (empty($action)) {
        throw new Exception('No action specified');
    }
    
    switch ($action) {
        case 'test_connection':
            echo json_encode([
                'success' => true,
                'message' => 'API endpoint is working',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            break;
            
        case 'get_policy_data':
            getPolicyData();
            break;
            
        case 'update_policy':
            updatePolicy();
            break;
            
        case 'delete_policy':
            deletePolicy();
            break;
            
        default:
            throw new Exception('Invalid action specified: ' . $action);
    }
    
} catch (Exception $e) {
    error_log("Policy operations error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    echo json_encode([
        'success' => false,
        'message' => 'Operation failed: ' . $e->getMessage(),
        'error_code' => 'OPERATION_ERROR',
        'debug_info' => [
            'action' => $_POST['action'] ?? 'not_set',
            'policy_id' => $_POST['policy_id'] ?? 'not_set',
            'timestamp' => date('Y-m-d H:i:s')
        ]
    ]);
}

if (isset($con)) {
    mysqli_close($con);
}

function getPolicyData() {
    global $con;
    
    $policyId = intval($_POST['policy_id'] ?? 0);
    
    if ($policyId <= 0) {
        throw new Exception('Invalid policy ID');
    }
    
    $query = "
        SELECT 
            id, name, phone, vehicle_number, vehicle_type, 
            insurance_company, policy_type, policy_issue_date,
            policy_start_date, policy_end_date, fc_expiry_date, 
            permit_expiry_date, premium, revenue, chassiss, comments,
            payout, customer_paid, discount, calculated_revenue,
            created_date, updated_at
        FROM policy 
        WHERE id = ?
    ";
    
    $stmt = mysqli_prepare($con, $query);
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . mysqli_error($con));
    }
    
    mysqli_stmt_bind_param($stmt, 'i', $policyId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (!$result) {
        throw new Exception('Failed to execute query: ' . mysqli_error($con));
    }
    
    $policy = mysqli_fetch_assoc($result);
    
    if (!$policy) {
        throw new Exception('Policy not found');
    }
    
    mysqli_stmt_close($stmt);
    
    // Convert numeric fields
    $numericFields = ['premium', 'revenue', 'payout', 'customer_paid', 'discount', 'calculated_revenue'];
    foreach ($numericFields as $field) {
        if (isset($policy[$field])) {
            $policy[$field] = floatval($policy[$field]);
        }
    }
    
    // Handle null dates
    $dateFields = ['fc_expiry_date', 'permit_expiry_date', 'policy_issue_date'];
    foreach ($dateFields as $field) {
        if ($policy[$field] === '0000-00-00' || empty($policy[$field])) {
            $policy[$field] = null;
        }
    }
    
    echo json_encode([
        'success' => true,
        'policy' => $policy,
        'loaded_at' => date('Y-m-d H:i:s')
    ]);
}

function updatePolicy() {
    global $con;
    
    $policyId = intval($_POST['policy_id'] ?? 0);
    
    if ($policyId <= 0) {
        throw new Exception('Invalid policy ID');
    }
    
    // Validate and sanitize input
    $name = mysqli_real_escape_string($con, trim($_POST['name'] ?? ''));
    $phone = mysqli_real_escape_string($con, trim($_POST['phone'] ?? ''));
    $vehicle_number = mysqli_real_escape_string($con, trim($_POST['vehicle_number'] ?? ''));
    $vehicle_type = mysqli_real_escape_string($con, trim($_POST['vehicle_type'] ?? ''));
    $insurance_company = mysqli_real_escape_string($con, trim($_POST['insurance_company'] ?? ''));
    $policy_type = mysqli_real_escape_string($con, trim($_POST['policy_type'] ?? ''));
    $policy_start_date = $_POST['policy_start_date'] ?? '';
    $policy_end_date = $_POST['policy_end_date'] ?? '';
    $premium = floatval($_POST['premium'] ?? 0);
    $comments = mysqli_real_escape_string($con, trim($_POST['comments'] ?? ''));
    
    // New financial fields
    $payout = floatval($_POST['payout'] ?? 0);
    $customer_paid = floatval($_POST['customer_paid'] ?? 0);
    $discount = floatval($_POST['discount'] ?? 0);
    $calculated_revenue = floatval($_POST['calculated_revenue'] ?? 0);
    
    // Calculate revenue if not provided
    if ($calculated_revenue == 0 && $payout > 0 && $discount > 0) {
        $calculated_revenue = $payout - $discount;
    }
    
    $revenue = $calculated_revenue;
    
    // Validate required fields
    if (empty($name) || empty($phone) || empty($vehicle_number) || empty($vehicle_type) || 
        empty($insurance_company) || empty($policy_type) || empty($policy_start_date) || 
        empty($policy_end_date) || $premium <= 0) {
        throw new Exception('Please fill all required fields properly');
    }
    
    // Validate phone number
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        throw new Exception('Please enter a valid 10-digit phone number');
    }
    
    // Check if vehicle number already exists for other policies
    $checkQuery = "SELECT id FROM policy WHERE vehicle_number = ? AND id != ?";
    $checkStmt = mysqli_prepare($con, $checkQuery);
    if ($checkStmt) {
        mysqli_stmt_bind_param($checkStmt, 'si', $vehicle_number, $policyId);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);
        
        if (mysqli_num_rows($checkResult) > 0) {
            mysqli_stmt_close($checkStmt);
            throw new Exception('Another policy already exists for this vehicle number');
        }
        mysqli_stmt_close($checkStmt);
    }
    
    // Start transaction
    mysqli_autocommit($con, false);
    
    try {
        // Update policy
        $updateQuery = "
            UPDATE policy SET 
                name = ?, phone = ?, vehicle_number = ?, vehicle_type = ?, 
                insurance_company = ?, policy_type = ?, policy_start_date = ?, 
                policy_end_date = ?, premium = ?, revenue = ?, comments = ?,
                payout = ?, customer_paid = ?, discount = ?, calculated_revenue = ?,
                updated_at = NOW()
            WHERE id = ?
        ";
        
        $updateStmt = mysqli_prepare($con, $updateQuery);
        if (!$updateStmt) {
            throw new Exception('Failed to prepare update statement: ' . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($updateStmt, 'ssssssssddsddddi', 
            $name, $phone, $vehicle_number, $vehicle_type, $insurance_company, 
            $policy_type, $policy_start_date, $policy_end_date, $premium, $revenue, 
            $comments, $payout, $customer_paid, $discount, $calculated_revenue, $policyId
        );
        
        if (!mysqli_stmt_execute($updateStmt)) {
            throw new Exception('Failed to update policy: ' . mysqli_stmt_error($updateStmt));
        }
        
        mysqli_stmt_close($updateStmt);
        
        // Update account software if revenue has changed
        $accountMsg = '';
        if ($revenue > 0) {
            try {
                include 'account.php';
                
                // Check if income record exists for this policy
                $incomeCheckQuery = "SELECT id, amount FROM income WHERE insurance_id = ?";
                $incomeStmt = $acc->prepare($incomeCheckQuery);
                if ($incomeStmt) {
                    $incomeStmt->bind_param('i', $policyId);
                    $incomeStmt->execute();
                    $incomeResult = $incomeStmt->get_result();
                    
                    if ($incomeResult->num_rows > 0) {
                        // Update existing income record
                        $incomeRow = $incomeResult->fetch_assoc();
                        $updateIncomeQuery = "UPDATE income SET amount = ?, received = ?, updated_at = NOW() WHERE insurance_id = ?";
                        $updateIncomeStmt = $acc->prepare($updateIncomeQuery);
                        if ($updateIncomeStmt) {
                            $updateIncomeStmt->bind_param('ddi', $revenue, $revenue, $policyId);
                            if ($updateIncomeStmt->execute()) {
                                $accountMsg = " ✅ Revenue updated in account software";
                            }
                            $updateIncomeStmt->close();
                        }
                    } else {
                        // Create new income record
                        $date = date('Y-m-d');
                        $description = 'Insurance';
                        $category = 'Insurance';
                        $subcategory = 'Insurance';
                        $balance = 0;
                        
                        $insertIncomeQuery = "INSERT INTO income (
                            date, name, phone, description, category, subcategory,
                            amount, received, balance, created_at, updated_at, insurance_id
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)";
                        
                        $insertIncomeStmt = $acc->prepare($insertIncomeQuery);
                        if ($insertIncomeStmt) {
                            $insertIncomeStmt->bind_param('ssssssdddi', 
                                $date, $name, $phone, $description, $category, $subcategory,
                                $revenue, $revenue, $balance, $policyId
                            );
                            if ($insertIncomeStmt->execute()) {
                                $accountMsg = " ✅ Revenue synced to account software";
                            }
                            $insertIncomeStmt->close();
                        }
                    }
                    $incomeStmt->close();
                }
                $acc->close();
            } catch (Exception $accError) {
                error_log("Account sync error during policy update: " . $accError->getMessage());
                $accountMsg = " ⚠️ Policy updated but revenue sync failed";
            }
        }
        
        // Add comment if provided
        if (!empty($comments)) {
            date_default_timezone_set("Asia/Calcutta");
            $time = date('Y-m-d H:i:s');
            $user = $_SESSION['username'] ?? 'System';
            
            $commentQuery = "INSERT INTO comments (policy_id, user, comments, date) VALUES (?, ?, ?, ?)";
            $commentStmt = mysqli_prepare($con, $commentQuery);
            if ($commentStmt) {
                mysqli_stmt_bind_param($commentStmt, 'isss', $policyId, $user, $comments, $time);
                mysqli_stmt_execute($commentStmt);
                mysqli_stmt_close($commentStmt);
            }
        }
        
        // Commit transaction
        mysqli_commit($con);
        
        echo json_encode([
            'success' => true,
            'message' => 'Policy updated successfully! 🎉' . $accountMsg,
            'policy_id' => $policyId,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($con);
        throw $e;
    }
    
    // Restore autocommit
    mysqli_autocommit($con, true);
}

function deletePolicy() {
    global $con;
    
    $policyId = intval($_POST['policy_id'] ?? 0);
    
    if ($policyId <= 0) {
        throw new Exception('Invalid policy ID');
    }
    
    // Start transaction
    mysqli_autocommit($con, false);
    
    try {
        // Delete related files
        $deleteFilesQuery = "DELETE FROM files WHERE policy_id = ?";
        $filesStmt = mysqli_prepare($con, $deleteFilesQuery);
        if ($filesStmt) {
            mysqli_stmt_bind_param($filesStmt, 'i', $policyId);
            mysqli_stmt_execute($filesStmt);
            mysqli_stmt_close($filesStmt);
        }
        
        // Delete related RC files
        $deleteRCQuery = "DELETE FROM rc WHERE policy_id = ?";
        $rcStmt = mysqli_prepare($con, $deleteRCQuery);
        if ($rcStmt) {
            mysqli_stmt_bind_param($rcStmt, 'i', $policyId);
            mysqli_stmt_execute($rcStmt);
            mysqli_stmt_close($rcStmt);
        }
        
        // Delete related comments
        $deleteCommentsQuery = "DELETE FROM comments WHERE policy_id = ?";
        $commentsStmt = mysqli_prepare($con, $deleteCommentsQuery);
        if ($commentsStmt) {
            mysqli_stmt_bind_param($commentsStmt, 'i', $policyId);
            mysqli_stmt_execute($commentsStmt);
            mysqli_stmt_close($commentsStmt);
        }
        
        // Delete from account software
        try {
            include 'account.php';
            $deleteIncomeQuery = "DELETE FROM income WHERE insurance_id = ?";
            $incomeStmt = $acc->prepare($deleteIncomeQuery);
            if ($incomeStmt) {
                $incomeStmt->bind_param('i', $policyId);
                $incomeStmt->execute();
                $incomeStmt->close();
            }
            $acc->close();
        } catch (Exception $accError) {
            error_log("Account deletion error: " . $accError->getMessage());
        }
        
        // Delete the policy
        $deletePolicyQuery = "DELETE FROM policy WHERE id = ?";
        $policyStmt = mysqli_prepare($con, $deletePolicyQuery);
        if (!$policyStmt) {
            throw new Exception('Failed to prepare delete statement: ' . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($policyStmt, 'i', $policyId);
        if (!mysqli_stmt_execute($policyStmt)) {
            throw new Exception('Failed to delete policy: ' . mysqli_stmt_error($policyStmt));
        }
        
        $affectedRows = mysqli_stmt_affected_rows($policyStmt);
        mysqli_stmt_close($policyStmt);
        
        if ($affectedRows === 0) {
            throw new Exception('Policy not found or already deleted');
        }
        
        // Commit transaction
        mysqli_commit($con);
        
        echo json_encode([
            'success' => true,
            'message' => 'Policy deleted successfully! 🗑️',
            'policy_id' => $policyId,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
        
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($con);
        throw $e;
    }
    
    // Restore autocommit
    mysqli_autocommit($con, true);
}
?>

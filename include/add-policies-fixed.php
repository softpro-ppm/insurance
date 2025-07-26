<?php
	require 'session.php';
	require 'config.php';

	// Simple input sanitization
	$name = mysqli_real_escape_string($con, trim($_POST['name']));
	$phone = mysqli_real_escape_string($con, trim($_POST['phone']));
	$vehicle_number = mysqli_real_escape_string($con, trim($_POST['vehicle_number']));
	$vehicle_type = mysqli_real_escape_string($con, trim($_POST['vehicle_type']));
	$insurance_company = mysqli_real_escape_string($con, trim($_POST['insurance_company']));
	$policy_type = mysqli_real_escape_string($con, trim($_POST['policy_type']));
	
	// Handle date inputs
	$policy_start_date = $_POST['policy_start_date'];
	$policy_end_date = $_POST['policy_end_date'];
	
	// Set defaults for backward compatibility
	$chassiss = '';
	$policy_issue_date = $policy_start_date;
	$fc_expiry_date = null;
	$permit_expiry_date = null;
	
if ($stmt->execute()) {
	$policy_id = $con->insert_id;
	// ...existing code for file uploads and account sync...
	// ...existing code for success message construction...
	// If AJAX, return JSON
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		header('Content-Type: application/json');
		echo json_encode(['success' => true, 'message' => $success_msg]);
		exit;
	}
	echo "<script>alert('" . addslashes($success_msg) . "')</script>";
	echo "<script>window.location.href='../policies.php';</script>";
} else {
	$error_msg = "Error adding policy: " . $con->error;
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		header('Content-Type: application/json');
		echo json_encode(['success' => false, 'message' => $error_msg]);
		exit;
	}
	echo "<script>alert('" . addslashes($error_msg) . "')</script>";
	echo "<script>window.history.back();</script>";
}
						$account_stmt->bind_param("sdss", $income_date, $revenue, $source, $details);
					} else {
						// Structure 3: minimal structure
						$account_sql = "INSERT INTO income (amount, source, details) VALUES (?, ?, ?)";
						$account_stmt = $acc->prepare($account_sql);
						$source = "Insurance Policy - NEW";
						$details = "Revenue from NEW policy: $vehicle_number ($name) - Added: " . date('Y-m-d H:i:s');
						$account_stmt->bind_param("dss", $revenue, $source, $details);
					}
					
					error_log("Executing account SQL: " . $account_sql);
					
					if ($account_stmt->execute()) {
						$sync_message = "✅ ₹$revenue synced to account software";
						// Log successful sync
						error_log("Account sync SUCCESS for policy $policy_id: ₹$revenue added to income table");
					} else {
						$sync_message = "❌ Account sync failed: " . $account_stmt->error;
						error_log("Account sync FAILED for policy $policy_id: " . $account_stmt->error);
					}
					$account_stmt->close();
				} else {
					$sync_message = "❌ Income table not found in account software";
					error_log("Income table not found in account database");
				}
				$acc->close();
			} catch (Exception $e) {
				// Account integration failed, but don't stop policy creation
				$sync_message = "❌ Account sync error: " . $e->getMessage();
				error_log("Account integration FAILED for policy $policy_id: " . $e->getMessage());
			}
		} else {
			$sync_message = "⚠️ No revenue to sync (Revenue = ₹0)";
		}
		
		$upload_message = "";
		if (!empty($upload_errors)) {
			$upload_message = " (File upload issues: " . implode(", ", $upload_errors) . ")";
		}
		
		// Enhanced success message with detailed information
		$success_msg = "Policy added successfully!\n\n";
		$success_msg .= "Vehicle: $vehicle_number\n";
		$success_msg .= "Customer: $name\n";
		$success_msg .= "Premium: ₹" . number_format($premium, 2) . "\n";
		$success_msg .= "Customer Paid: ₹" . number_format($customer_paid, 2) . "\n";
		$success_msg .= "Discount: ₹" . number_format($discount, 2) . "\n";
		$success_msg .= "Revenue: ₹" . number_format($revenue, 2) . "\n";
		$success_msg .= "\nAccount Integration: " . $sync_message . "\n";
		
		if (!empty($upload_errors)) {
			$success_msg .= "\nFile Upload Issues: " . implode(", ", $upload_errors);
		} else {
			$file_count = 0;
			if (isset($_FILES['files']) && !empty($_FILES['files']['name'][0])) {
				$file_count += count(array_filter($_FILES['files']['name']));
			}
			if (isset($_FILES['rc']) && !empty($_FILES['rc']['name'][0])) {
				$file_count += count(array_filter($_FILES['rc']['name']));
			}
			if ($file_count > 0) {
				$success_msg .= "\nFiles Uploaded: $file_count documents uploaded successfully";
			}
		}
		
		echo "<script>alert('" . addslashes($success_msg) . "')</script>";
		echo "<script>window.location.href='../policies.php';</script>";
	} else {
		$error_msg = "Error adding policy: " . $con->error;
		echo "<script>alert('" . addslashes($error_msg) . "')</script>";
		echo "<script>window.history.back();</script>";
	}

	$stmt->close();
	$con->close();
?>

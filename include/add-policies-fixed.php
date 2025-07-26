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
	
	$premium = !empty($_POST['premium']) ? floatval($_POST['premium']) : 0;
	
	// NEW LOGIC: Financial fields for new policies
	$payout = !empty($_POST['payout']) ? floatval($_POST['payout']) : 0;
	$customer_paid = !empty($_POST['customer_paid']) ? floatval($_POST['customer_paid']) : 0;
	$discount = !empty($_POST['discount']) ? floatval($_POST['discount']) : 0;
	$calculated_revenue = !empty($_POST['calculated_revenue']) ? floatval($_POST['calculated_revenue']) : 0;
	
	// DEBUG: Log received values
	error_log("ADD POLICY DEBUG - Received values:");
	error_log("Premium: $premium, Payout: $payout, Customer Paid: $customer_paid");
	error_log("Discount (from form): $discount, Calculated Revenue (from form): $calculated_revenue");
	
	// Auto-calculate using CORRECT new policy logic
	if ($discount == 0 && $premium > 0 && $customer_paid > 0) {
		$discount = $premium - $customer_paid;
		error_log("Auto-calculated Discount: $discount");
	}
	
	if ($calculated_revenue == 0 && $payout > 0 && $discount >= 0) {
		$calculated_revenue = $payout - $discount;
		error_log("Auto-calculated Revenue: $calculated_revenue");
	}
	
	// BACKWARD COMPATIBILITY: Use old logic for revenue field if new fields not provided
	if ($payout == 0 || $customer_paid == 0) {
		// Old logic: revenue = some default or manual entry
		$revenue = !empty($_POST['revenue']) ? floatval($_POST['revenue']) : 0;
		error_log("Using OLD logic - Revenue: $revenue");
	} else {
		// NEW POLICY LOGIC: Revenue = Payout - Discount
		// Example: Premium=10000, CustomerPaid=8000, Payout=3000
		// Discount = Premium - CustomerPaid = 10000-8000 = 2000
		// Revenue = Payout - Discount = 3000-2000 = 1000
		$revenue = $calculated_revenue;
		error_log("Using NEW logic - Final Revenue: $revenue (Payout: $payout - Discount: $discount)");
	}
	
	$comments = mysqli_real_escape_string($con, trim($_POST['comments']));

	// Validate required fields
	if (empty($name) || empty($phone) || empty($vehicle_number) || empty($vehicle_type) || empty($insurance_company) || empty($policy_type) || empty($policy_start_date) || empty($policy_end_date) || $premium <= 0) {
		echo "<script>alert('Please fill all required fields properly.')</script>";
		echo "<script>window.history.back();</script>";
		exit;
	}

	// Validate phone number
	if (!preg_match('/^[0-9]{10}$/', $phone)) {
		echo "<script>alert('Please enter a valid 10-digit phone number.')</script>";
		echo "<script>window.history.back();</script>";
		exit;
	}

	// Check if policy already exists
	$checkSql = "SELECT id FROM policy WHERE vehicle_number = ?";
	$checkStmt = $con->prepare($checkSql);
	$checkStmt->bind_param("s", $vehicle_number);
	$checkStmt->execute();
	$checkResult = $checkStmt->get_result();
	
	if($checkResult->num_rows > 0){
		echo "<script>alert('Policy already exists for this vehicle number. Please use a different vehicle number or update the existing policy.')</script>";
		echo "<script>window.history.back();</script>";
		exit;
	}

	// Prepare INSERT statement
	$insertSql = "INSERT INTO policy (
		name, phone, vehicle_number, vehicle_type, insurance_company, policy_type, 
		policy_issue_date, policy_start_date, policy_end_date, fc_expiry_date, permit_expiry_date, 
		premium, revenue, payout, customer_paid, discount, calculated_revenue, 
		created_date, chassiss, comments, updated_at
	) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), ?, ?, NOW())";

	$stmt = $con->prepare($insertSql);
	$stmt->bind_param(
		"sssssssssssdddddsss",
		$name, $phone, $vehicle_number, $vehicle_type, $insurance_company, $policy_type,
		$policy_issue_date, $policy_start_date, $policy_end_date, $fc_expiry_date, $permit_expiry_date,
		$premium, $revenue, $payout, $customer_paid, $discount, $calculated_revenue,
		$chassiss, $comments
	);

	if ($stmt->execute()) {
		$policy_id = $con->insert_id;
		
		// HANDLE FILE UPLOADS AFTER POLICY CREATION
		$upload_errors = [];
		
		// Handle Policy Documents (files[])
		if (isset($_FILES['files']) && !empty($_FILES['files']['name'][0])) {
			$docx = array();
			foreach(array_filter($_FILES['files']['name']) as $d => $document) {
				$allowedExtensions = array('pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx');
				$file_extension = strtolower(pathinfo($document, PATHINFO_EXTENSION));
				
				if (in_array($file_extension, $allowedExtensions)) {
					$filename = time() . '_' . $d . '_' . $document;
					$targetPath = '../assets/uploads/' . $filename;
					
					if (move_uploaded_file($_FILES['files']['tmp_name'][$d], $targetPath)) {
						$docx[] = "('" . $policy_id . "', '" . $filename . "')";
					} else {
						$upload_errors[] = "Failed to upload policy document: " . $document;
					}
				} else {
					$upload_errors[] = "Invalid file type for policy document: " . $document;
				}
			}
			
			if (!empty($docx)) {
				$docx_sql = "INSERT INTO files (policy_id, files) VALUES " . implode(',', $docx);
				mysqli_query($con, $docx_sql);
			}
		}
		
		// Handle RC Documents (rc[])
		if (isset($_FILES['rc']) && !empty($_FILES['rc']['name'][0])) {
			$rcs = array();
			foreach(array_filter($_FILES['rc']['name']) as $d => $rc_document) {
				$allowedExtensions = array('pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx');
				$file_extension = strtolower(pathinfo($rc_document, PATHINFO_EXTENSION));
				
				if (in_array($file_extension, $allowedExtensions)) {
					$filename = time() . '_rc_' . $d . '_' . $rc_document;
					$targetPath = '../assets/uploads/' . $filename;
					
					if (move_uploaded_file($_FILES['rc']['tmp_name'][$d], $targetPath)) {
						$rcs[] = "('" . $policy_id . "', '" . $filename . "')";
					} else {
						$upload_errors[] = "Failed to upload RC document: " . $rc_document;
					}
				} else {
					$upload_errors[] = "Invalid file type for RC document: " . $rc_document;
				}
			}
			
			if (!empty($rcs)) {
				$rc_sql = "INSERT INTO rc (policy_id, files) VALUES " . implode(',', $rcs);
				mysqli_query($con, $rc_sql);
			}
		}
		
		// ACCOUNT INTEGRATION: Only sync revenue for NEW policies added from today onwards
		// Old policies (added before today) will NEVER sync to account software
		$sync_message = "";
		if ($revenue > 0) {
			try {
				include_once 'account.php';
				
				// Debug: Log that we're attempting account sync
				error_log("Attempting account sync for policy $policy_id with revenue: $revenue");
				
				// Check if income table exists and get its structure
				$table_check = $acc->query("SHOW TABLES LIKE 'income'");
				if ($table_check->num_rows > 0) {
					// Get column structure
					$columns = $acc->query("DESCRIBE income");
					$column_names = [];
					while ($col = $columns->fetch_assoc()) {
						$column_names[] = $col['Field'];
					}
					
					error_log("Income table columns: " . implode(", ", $column_names));
					
					// Adapt to different possible column structures
					if (in_array('income_date', $column_names)) {
						// Structure 1: income_date column exists
						$account_sql = "INSERT INTO income (income_date, amount, source, details, created_at) VALUES (?, ?, ?, ?, NOW())";
						$account_stmt = $acc->prepare($account_sql);
						$income_date = date('Y-m-d');
						$source = "Insurance Policy - NEW";
						$details = "Revenue from NEW policy: $vehicle_number ($name) - Added: " . date('Y-m-d H:i:s');
						$account_stmt->bind_param("sdss", $income_date, $revenue, $source, $details);
					} elseif (in_array('date', $column_names)) {
						// Structure 2: date column exists
						$account_sql = "INSERT INTO income (date, amount, source, details, created_at) VALUES (?, ?, ?, ?, NOW())";
						$account_stmt = $acc->prepare($account_sql);
						$income_date = date('Y-m-d');
						$source = "Insurance Policy - NEW";
						$details = "Revenue from NEW policy: $vehicle_number ($name) - Added: " . date('Y-m-d H:i:s');
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

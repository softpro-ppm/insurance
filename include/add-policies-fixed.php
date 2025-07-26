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
	
	// Auto-calculate if values provided
	if ($discount == 0 && $premium > 0 && $customer_paid > 0) {
		$discount = $premium - $customer_paid;
	}
	
	if ($calculated_revenue == 0 && $payout > 0 && $discount >= 0) {
		$calculated_revenue = $payout - $discount;
	}
	
	// BACKWARD COMPATIBILITY: Use old logic for revenue field if new fields not provided
	if ($payout == 0 || $customer_paid == 0) {
		// Old logic: revenue = some default or manual entry
		$revenue = !empty($_POST['revenue']) ? floatval($_POST['revenue']) : 0;
	} else {
		// New logic: revenue = calculated_revenue
		$revenue = $calculated_revenue;
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
		
		// ACCOUNT INTEGRATION: Only sync revenue for NEW policies added from today onwards
		// Old policies (added before today) will NEVER sync to account software
		if ($revenue > 0) {
			try {
				include 'account.php';
				
				// Check if income table exists and get its structure
				$table_check = $acc->query("SHOW TABLES LIKE 'income'");
				if ($table_check->num_rows > 0) {
					// Get column structure
					$columns = $acc->query("DESCRIBE income");
					$column_names = [];
					while ($col = $columns->fetch_assoc()) {
						$column_names[] = $col['Field'];
					}
					
					// Adapt to different possible column structures
					if (in_array('income_date', $column_names)) {
						// Structure 1: income_date column exists
						$account_sql = "INSERT INTO income (income_date, amount, source, details, created_at) VALUES (?, ?, ?, ?, NOW())";
						$account_stmt = $acc->prepare($account_sql);
						$income_date = date('Y-m-d');
						$source = "Insurance Policy - NEW";
						$details = "Revenue from NEW policy: $vehicle_number ($name) - Added: " . date('Y-m-d');
						$account_stmt->bind_param("sdss", $income_date, $revenue, $source, $details);
					} elseif (in_array('date', $column_names)) {
						// Structure 2: date column exists
						$account_sql = "INSERT INTO income (date, amount, source, details, created_at) VALUES (?, ?, ?, ?, NOW())";
						$account_stmt = $acc->prepare($account_sql);
						$income_date = date('Y-m-d');
						$source = "Insurance Policy - NEW";
						$details = "Revenue from NEW policy: $vehicle_number ($name) - Added: " . date('Y-m-d');
						$account_stmt->bind_param("sdss", $income_date, $revenue, $source, $details);
					} else {
						// Structure 3: minimal structure
						$account_sql = "INSERT INTO income (amount, source, details) VALUES (?, ?, ?)";
						$account_stmt = $acc->prepare($account_sql);
						$source = "Insurance Policy - NEW";
						$details = "Revenue from NEW policy: $vehicle_number ($name) - Added: " . date('Y-m-d');
						$account_stmt->bind_param("dss", $revenue, $source, $details);
					}
					
					$account_stmt->execute();
					$account_stmt->close();
				}
				$acc->close();
			} catch (Exception $e) {
				// Account integration failed, but don't stop policy creation
				error_log("Account integration failed: " . $e->getMessage());
			}
		}
		
		echo "<script>alert('Policy added successfully! Revenue: ₹$revenue')</script>";
		echo "<script>window.location.href='../policies.php';</script>";
	} else {
		echo "<script>alert('Error adding policy: " . $con->error . "')</script>";
		echo "<script>window.history.back();</script>";
	}

	$stmt->close();
	$con->close();
?>

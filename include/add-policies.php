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
	
	// Handle date inputs (already in Y-m-d format from HTML5 date inputs)
	$policy_start_date = $_POST['policy_start_date'];
	$policy_end_date = $_POST['policy_end_date'];
	
	// Removed fields - set defaults for backward compatibility
	$chassiss = ''; // No longer collected from frontend
	$policy_issue_date = $policy_start_date; // Use start date as issue date
	$fc_expiry_date = null; // No longer collected from frontend
	$permit_expiry_date = null; // No longer collected from frontend
	
	$premium = !empty($_POST['premium']) ? floatval($_POST['premium']) : 0;
	
	// New financial fields
	$payout = !empty($_POST['payout']) ? floatval($_POST['payout']) : 0;
	$customer_paid = !empty($_POST['customer_paid']) ? floatval($_POST['customer_paid']) : 0;
	$discount = !empty($_POST['discount']) ? floatval($_POST['discount']) : 0;
	$calculated_revenue = !empty($_POST['calculated_revenue']) ? floatval($_POST['calculated_revenue']) : 0;
	
	// Debug: Log the values
	error_log("DEBUG - Premium: $premium, Payout: $payout, Customer Paid: $customer_paid, Discount: $discount, Calculated Revenue: $calculated_revenue");
	
	// Calculate discount and revenue manually if not provided from frontend
	if ($discount == 0 && $premium > 0 && $customer_paid > 0) {
		$discount = $premium - $customer_paid;
	}
	
	if ($calculated_revenue == 0 && $payout > 0 && $discount > 0) {
		$calculated_revenue = $payout - $discount;
	}
	
	// Use calculated revenue as the final revenue value
	$revenue = $calculated_revenue > 0 ? $calculated_revenue : 0;
	
	// Final debug log
	error_log("FINAL VALUES - Discount: $discount, Revenue: $revenue");
	
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
	$checkSql = "SELECT id FROM policy WHERE vehicle_number = '$vehicle_number'";
	$checkResult = mysqli_query($con, $checkSql);
	
	if(mysqli_num_rows($checkResult) > 0){
		echo "<script>alert('Policy already exists for this vehicle number. Please use a different vehicle number or update the existing policy.')</script>";
		echo "<script>window.history.back();</script>";
		exit;
	}

	// Insert new policy with enhanced financial fields
	$insertSql = "INSERT INTO policy (
		name, phone, vehicle_number, vehicle_type, insurance_company, policy_type, 
		policy_issue_date, policy_start_date, policy_end_date, fc_expiry_date, permit_expiry_date, 
		premium, revenue, payout, customer_paid, discount, calculated_revenue, 
		created_date, chassiss, comments, updated_at
	) VALUES (
		'$name', '$phone', '$vehicle_number', '$vehicle_type', '$insurance_company', '$policy_type', 
		'$policy_issue_date', '$policy_start_date', '$policy_end_date', 
		" . (!empty($fc_expiry_date) ? "'$fc_expiry_date'" : "NULL") . ", 
		" . (!empty($permit_expiry_date) ? "'$permit_expiry_date'" : "NULL") . ", 
		$premium, $revenue, 
		" . ($payout > 0 ? $payout : "NULL") . ", 
		" . ($customer_paid > 0 ? $customer_paid : "NULL") . ", 
		" . ($discount > 0 ? $discount : "NULL") . ", 
		" . ($calculated_revenue > 0 ? $calculated_revenue : "NULL") . ", 
		NOW(), '$chassiss', '$comments', NOW()
	)";

	$result = mysqli_query($con, $insertSql);
	
	// If the enhanced query fails (columns don't exist), try the legacy query
	if (!$result) {
		$error = mysqli_error($con);
		
		// Check if error is about unknown columns
		if (strpos($error, "Unknown column") !== false) {
			echo "<!-- DEBUG: Using legacy insert query -->";
			
			// Legacy insert query (original columns only)
			$legacyInsertSql = "INSERT INTO policy (
				name, phone, vehicle_number, vehicle_type, insurance_company, policy_type, 
				policy_issue_date, policy_start_date, policy_end_date, fc_expiry_date, permit_expiry_date, 
				premium, revenue, created_date, chassiss
			) VALUES (
				'$name', '$phone', '$vehicle_number', '$vehicle_type', '$insurance_company', '$policy_type', 
				'$policy_issue_date', '$policy_start_date', '$policy_end_date', 
				" . (!empty($fc_expiry_date) ? "'$fc_expiry_date'" : "NULL") . ", 
				" . (!empty($permit_expiry_date) ? "'$permit_expiry_date'" : "NULL") . ", 
				$premium, $revenue, NOW(), '$chassiss'
			)";
			
			$result = mysqli_query($con, $legacyInsertSql);
		}
	}
	
	if ($result) {
		$policy_id = mysqli_insert_id($con);
		echo "<!-- DEBUG: Policy inserted successfully with ID: $policy_id -->";
	} else {
		// Debug: Show the actual error
		$error = mysqli_error($con);
		echo "<script>alert('Database Error: $error');</script>";
		echo "<script>console.log('SQL Error: $error');</script>";
		echo "<script>console.log('SQL Query: $insertSql');</script>";
		echo "<script>window.location.href='../policies.php';</script>";
		exit;
	}
		
		
		// inserting data in account 
		echo "<!-- DEBUG: Starting account integration -->";
		echo "<!-- DEBUG: Revenue value: $revenue -->";
		
		try {
			// Include your database connection
			include 'account.php'; // $acc connection defined here
			
			// Sample or dynamic input values
			$date = date('Y-m-d');
			$description = 'Insurance';
			$category = 'Insurance';
			$subcategory = 'Insurance';
			$amount = $revenue;       // Use the calculated revenue
			$received = $revenue;     // Same as amount for now
			$balance = 0;             // Balance is 0 since amount = received
			$insurance_id = $policy_id; // Policy ID from insurance database
			
			echo "<!-- DEBUG: Amount to insert: $amount -->";
			
			// Check if all required values are present
			if ($amount > 0) {
				// Prepare the INSERT query
				$stmt = $acc->prepare("INSERT INTO income (
					date, name, phone, description, category, subcategory,
					amount, received, balance, created_at, updated_at, insurance_id
				) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
				
				// Bind parameters (check parameter count)
				$stmt->bind_param(
					"ssssssdddi",
					$date, $name, $phone, $description,
					$category, $subcategory, $amount, $received, $balance, $insurance_id
				);
				
				// Execute
				if ($stmt->execute()) {
					echo "<!-- ✅ Income record inserted successfully with amount: $amount -->";
				} else {
					echo "<!-- ❌ Error inserting record: " . $stmt->error . " -->";
				}
				
				// Close statement
				$stmt->close();
			} else {
				echo "<!-- ⚠️ Revenue is 0, skipping account insertion -->";
			}
			
			// Close connection
			$acc->close();
			
		} catch (Exception $e) {
			echo "<!-- ❌ Account integration error: " . $e->getMessage() . " -->";
		}
                


		
		
		// end account section

		$docx =  array();
		foreach(array_filter($_FILES['files']['name']) as $d => $document){
			$file_name = mysqli_real_escape_string($con,$_FILES['files']['name'][$d]);
			$files = time().$file_name;
			$upload_dir = "../assets/uploads/";
			$uploaded_file = $upload_dir.$files;
				
				move_uploaded_file($_FILES['files']['tmp_name'][$d],$uploaded_file);

			$docx[]="('".$policy_id."', '".$files."')";
		}

		if(count($docx)){
			$docx_sql = "insert into files (policy_id, files)
					values".implode(',',$docx);				
			
			mysqli_query($con,$docx_sql);
			
		}
		
		
		$rcs =  array();
		foreach(array_filter($_FILES['rc']['name']) as $d => $document){
			$file_name = mysqli_real_escape_string($con,$_FILES['rc']['name'][$d]);
			$rc = time().$file_name;
			$upload_dir = "../assets/uploads/";
			$uploaded_file = $upload_dir.$rc;
				
				move_uploaded_file($_FILES['rc']['tmp_name'][$d],$uploaded_file);

			$rcs[]="('".$policy_id."', '".$rc."')";
		}

		if(count($rcs)){
			$docx_sql = "insert into rc (policy_id, files)
					values".implode(',',$rcs);				
			
			mysqli_query($con,$docx_sql);
			
		}

		// Handle comments
		if(!empty($comments)){
			date_default_timezone_set("Asia/Calcutta");
		    $time = date('Y-m-d H:i:s');
			$commentSql = "INSERT INTO comments (policy_id, user, comments, date) VALUES ('$policy_id', '".$_SESSION['username']."', '$comments', '$time')";
			mysqli_query($con, $commentSql);
		}

		// Success response
		echo "<script>alert('Policy added successfully! 🎉')</script>";
		echo "<script>window.location.href='../home.php';</script>";
		
	} else {
		// Error handling
		echo "<script>alert('An error occurred while adding the policy. Please try again. Error: " . mysqli_error($con) . "')</script>";
		echo "<script>window.history.back();</script>";
	}
?>
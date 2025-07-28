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
	
	// Handle optional fields that are now collected from frontend
	$chassiss = !empty($_POST['chassiss']) ? mysqli_real_escape_string($con, trim($_POST['chassiss'])) : '';
	$policy_issue_date = !empty($_POST['policy_issue_date']) ? $_POST['policy_issue_date'] : $policy_start_date;
	$fc_expiry_date = !empty($_POST['fc_expiry_date']) ? $_POST['fc_expiry_date'] : null;
	$permit_expiry_date = !empty($_POST['permit_expiry_date']) ? $_POST['permit_expiry_date'] : null;
	
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
		$error_msg = "Please fill all required fields properly.";
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			header('Content-Type: application/json');
			echo json_encode(['success' => false, 'message' => $error_msg]);
			exit;
		}
		echo "<script>alert('$error_msg')</script>";
		echo "<script>window.history.back();</script>";
		exit;
	}

	// Validate phone number
	if (!preg_match('/^[0-9]{10}$/', $phone)) {
		$error_msg = "Please enter a valid 10-digit phone number.";
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			header('Content-Type: application/json');
			echo json_encode(['success' => false, 'message' => $error_msg]);
			exit;
		}
		echo "<script>alert('$error_msg')</script>";
		echo "<script>window.history.back();</script>";
		exit;
	}

	// Check if policy already exists
	$checkSql = "SELECT id FROM policy WHERE vehicle_number = '$vehicle_number'";
	$checkResult = mysqli_query($con, $checkSql);
	
	if(mysqli_num_rows($checkResult) > 0){
		$error_msg = "Policy already exists for this vehicle number. Please use a different vehicle number or update the existing policy.";
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			header('Content-Type: application/json');
			echo json_encode(['success' => false, 'message' => $error_msg]);
			exit;
		}
		echo "<script>alert('$error_msg')</script>";
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
	$success_msg = "Policy added successfully! 🎉";
	$account_msg = "";
	
	if ($result) {
		$policy_id = mysqli_insert_id($con);
		error_log("DEBUG: Policy inserted successfully with ID: $policy_id");
		
		// Account integration
		error_log("DEBUG: Starting account integration");
		error_log("DEBUG: Revenue value: $revenue");
		
		if ($revenue > 0) {
			try {
				// Include account database connection
				include 'account.php'; // $acc connection defined here
				
				// Prepare data
				$date = date('Y-m-d');
				$description = 'Insurance';
				$category = 'Insurance';
				$subcategory = 'Insurance';
				$amount = $revenue;
				$received = $revenue;
				$balance = 0;
				$insurance_id = $policy_id;
				
				error_log("DEBUG: Amount to insert: $amount");
				
				// Prepare the INSERT query
				$stmt = $acc->prepare("INSERT INTO income (
					date, name, phone, description, category, subcategory,
					amount, received, balance, created_at, updated_at, insurance_id
				) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
				
				if ($stmt) {
					// Bind parameters
					$stmt->bind_param(
						"ssssssdddi",
						$date, $name, $phone, $description,
						$category, $subcategory, $amount, $received, $balance, $insurance_id
					);
					
					// Execute
					if ($stmt->execute()) {
						error_log("✅ Income record inserted successfully with amount: $amount");
						$account_msg = " ✅ ₹" . number_format($amount, 2) . " synced to account software";
					} else {
						error_log("❌ Error inserting record: " . $stmt->error);
						$account_msg = " ⚠️ Policy added but revenue sync failed";
					}
					
					// Close statement
					$stmt->close();
				} else {
					error_log("❌ Error preparing statement: " . $acc->error);
					$account_msg = " ⚠️ Policy added but revenue sync failed";
				}
				
				// Close connection
				$acc->close();
				
			} catch (Exception $e) {
				error_log("❌ Account integration error: " . $e->getMessage());
				$account_msg = " ⚠️ Policy added but revenue sync failed";
			}
		} else {
			error_log("⚠️ Revenue is 0, skipping account insertion");
		}
		
		// Update success message with account info
		$success_msg .= $account_msg;
		
		// Handle file uploads
		$docx = array();
		foreach(array_filter($_FILES['files']['name'] ?? []) as $d => $document){
			$file_name = mysqli_real_escape_string($con,$_FILES['files']['name'][$d]);
			$files = time().$file_name;
			$upload_dir = "../assets/uploads/";
			$uploaded_file = $upload_dir.$files;
				
			move_uploaded_file($_FILES['files']['tmp_name'][$d],$uploaded_file);
			$docx[] = "('".$policy_id."', '".$files."')";
		}

		if(count($docx)){
			$docx_sql = "insert into files (policy_id, files)
					values".implode(',',$docx);
			
			mysqli_query($con,$docx_sql);
		}
		
		// Handle RC uploads
		$rcs = array();
		foreach(array_filter($_FILES['rc']['name'] ?? []) as $d => $document){
			$file_name = mysqli_real_escape_string($con,$_FILES['rc']['name'][$d]);
			$rc = time().$file_name;
			$upload_dir = "../assets/uploads/";
			$uploaded_file = $upload_dir.$rc;
				
			move_uploaded_file($_FILES['rc']['tmp_name'][$d],$uploaded_file);
			$rcs[] = "('".$policy_id."', '".$rc."')";
		}

		if(count($rcs)){
			$docx_sql = "insert into rc (policy_id, files)
					values".implode(',',$rcs);
			
			mysqli_query($con,$docx_sql);
		}

		// Handle Aadhar Card uploads
		if(isset($_FILES['aadhar_card']) && !empty($_FILES['aadhar_card']['name'])){
			$aadhar_file = $_FILES['aadhar_card'];
			
			// Validate file type and size
			$allowed_types = array('image/jpeg', 'image/jpg', 'image/png');
			$max_size = 2 * 1024 * 1024; // 2MB
			
			if(in_array($aadhar_file['type'], $allowed_types) && $aadhar_file['size'] <= $max_size){
				$file_extension = pathinfo($aadhar_file['name'], PATHINFO_EXTENSION);
				$aadhar_filename = 'aadhar_' . $policy_id . '_' . time() . '.' . $file_extension;
				$aadhar_upload_dir = "../assets/uploads/aadhar/";
				
				// Create directory if it doesn't exist
				if(!is_dir($aadhar_upload_dir)){
					mkdir($aadhar_upload_dir, 0755, true);
				}
				
				$aadhar_uploaded_file = $aadhar_upload_dir . $aadhar_filename;
				
				if(move_uploaded_file($aadhar_file['tmp_name'], $aadhar_uploaded_file)){
					// Store in database
					$aadhar_sql = "INSERT INTO policy_documents (policy_id, document_type, file_name, file_path, created_at) 
								  VALUES ('$policy_id', 'aadhar_card', '$aadhar_filename', 'assets/uploads/aadhar/$aadhar_filename', NOW())";
					mysqli_query($con, $aadhar_sql);
					error_log("✅ Aadhar card uploaded successfully: $aadhar_filename");
				} else {
					error_log("❌ Failed to upload Aadhar card");
				}
			} else {
				error_log("❌ Aadhar card validation failed - invalid type or size");
			}
		}

		// Handle PAN Card uploads
		if(isset($_FILES['pan_card']) && !empty($_FILES['pan_card']['name'])){
			$pan_file = $_FILES['pan_card'];
			
			// Validate file type and size
			$allowed_types = array('image/jpeg', 'image/jpg', 'image/png');
			$max_size = 2 * 1024 * 1024; // 2MB
			
			if(in_array($pan_file['type'], $allowed_types) && $pan_file['size'] <= $max_size){
				$file_extension = pathinfo($pan_file['name'], PATHINFO_EXTENSION);
				$pan_filename = 'pan_' . $policy_id . '_' . time() . '.' . $file_extension;
				$pan_upload_dir = "../assets/uploads/pan/";
				
				// Create directory if it doesn't exist
				if(!is_dir($pan_upload_dir)){
					mkdir($pan_upload_dir, 0755, true);
				}
				
				$pan_uploaded_file = $pan_upload_dir . $pan_filename;
				
				if(move_uploaded_file($pan_file['tmp_name'], $pan_uploaded_file)){
					// Store in database
					$pan_sql = "INSERT INTO policy_documents (policy_id, document_type, file_name, file_path, created_at) 
							   VALUES ('$policy_id', 'pan_card', '$pan_filename', 'assets/uploads/pan/$pan_filename', NOW())";
					mysqli_query($con, $pan_sql);
					error_log("✅ PAN card uploaded successfully: $pan_filename");
				} else {
					error_log("❌ Failed to upload PAN card");
				}
			} else {
				error_log("❌ PAN card validation failed - invalid type or size");
			}
		}

		// Handle comments
		if(!empty($comments)){
			date_default_timezone_set("Asia/Calcutta");
			$time = date('Y-m-d H:i:s');
			$commentSql = "INSERT INTO comments (policy_id, user, comments, date) VALUES ('$policy_id', '".$_SESSION['username']."', '$comments', '$time')";
			mysqli_query($con, $commentSql);
		}

		// Check if AJAX request and return JSON response
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			header('Content-Type: application/json');
			echo json_encode(['success' => true, 'message' => $success_msg]);
			exit;
		}
		
		// If not AJAX, redirect
		echo "<script>alert('" . addslashes($success_msg) . "')</script>";
		echo "<script>window.location.href='../policies.php';</script>";
	} else {
		// Error handling
		$error_msg = "Error adding policy: " . mysqli_error($con);
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			header('Content-Type: application/json');
			echo json_encode(['success' => false, 'message' => $error_msg]);
			exit;
		}
		echo "<script>alert('" . addslashes($error_msg) . "')</script>";
		echo "<script>window.history.back();</script>";
	}
?>

<?php
	require 'session.php';
	require 'config.php';

	$name = $_POST['name'];
	$chassiss = $_POST['chassiss'];
	$phone = $_POST['phone'];
	$vehicle_number = $_POST['vehicle_number'];
	$vehicle_type = $_POST['vehicle_type'];
	$insurance_company = $_POST['insurance_company'];
	$policy_type = $_POST['policy_type'];
	$policy_issue_date = date('Y-m-d',strtotime($_POST['policy_issue_date']));
	$policy_start_date = date('Y-m-d',strtotime($_POST['policy_start_date']));
	$policy_end_date = date('Y-m-d',strtotime($_POST['policy_end_date']));
	if(!empty($_POST['fc_expiry_date'])){
		$fc_expiry_date = date('Y-m-d',strtotime($_POST['fc_expiry_date']));
	}else{
		$fc_expiry_date = '';
	}
	if(!empty($_POST['permit_expiry_date'])){
	$permit_expiry_date = date('Y-m-d',strtotime($_POST['permit_expiry_date']));
	}else{
	$permit_expiry_date = '';
	}
	
	// Financial fields from POST data
	$premium = isset($_POST['premium']) ? (float)$_POST['premium'] : 0;
	$payout = isset($_POST['payout']) ? (float)$_POST['payout'] : 0;
	$customer_paid = isset($_POST['customer_paid']) ? (float)$_POST['customer_paid'] : 0;
	$discount = isset($_POST['discount']) ? (float)$_POST['discount'] : 0;
	
	// Calculate revenue
	$calculated_revenue = $premium - $payout - $discount;
	
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
		
            	
            
                // Include your database connection
                include 'account.php'; // $acc connection defined here
                
                // Sample or dynamic input values
                $date = date('Y-m-d');
                //$name = ''; // Fill from $_POST or leave blank if not used
                //$phone = ''; // Fill from $_POST or leave blank if not used
                $description = 'Insurance';
                $category = 'Insurance';
                $subcategory = 'Insurance';
                $amount = $revenue;       // Example: 10000.00
                $received = $revenue;     // Example: 10000.00
                $balance = 0;             // Can also calculate: $amount - $received
                $insurance_id = $policy_id; // Must be defined before
                
                // Prepare the INSERT query
                $stmt = $acc->prepare("INSERT INTO income (
                    date, name, phone, description, category, subcategory,
                    amount, received, balance, created_at, updated_at, insurance_id
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
                
                // Bind parameters
                $stmt->bind_param(
                    "ssssssddds",
                    $date, $name, $phone, $description,
                    $category, $subcategory, $amount, $received, $balance, $insurance_id
                );
                
                // Execute
                if ($stmt->execute()) {
                    echo "✅ Income record inserted successfully!";
                } else {
                    echo "❌ Error inserting record: " . $stmt->error;
                }
                
                // Close
                $stmt->close();
                $acc->close();
                


		
		
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
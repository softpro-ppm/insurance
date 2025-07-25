<?php
	require 'session.php';
	require 'config.php';

	// Simple input sanitization
	$name = mysqli_real_escape_string($con, trim($_POST['name']));
	$chassiss = mysqli_real_escape_string($con, trim($_POST['chassiss']));
	$phone = mysqli_real_escape_string($con, trim($_POST['phone']));
	$vehicle_number = mysqli_real_escape_string($con, trim($_POST['vehicle_number']));
	$vehicle_type = mysqli_real_escape_string($con, trim($_POST['vehicle_type']));
	$insurance_company = mysqli_real_escape_string($con, trim($_POST['insurance_company']));
	$policy_type = mysqli_real_escape_string($con, trim($_POST['policy_type']));
	
	// Handle date inputs (already in Y-m-d format from HTML5 date inputs)
	$policy_issue_date = $_POST['policy_issue_date'];
	$policy_start_date = $_POST['policy_start_date'];
	$policy_end_date = $_POST['policy_end_date'];
	
	// Optional dates
	$fc_expiry_date = !empty($_POST['fc_expiry_date']) ? $_POST['fc_expiry_date'] : '';
	$permit_expiry_date = !empty($_POST['permit_expiry_date']) ? $_POST['permit_expiry_date'] : '';
	
	$premium = !empty($_POST['premium']) ? floatval($_POST['premium']) : 0;
	$revenue = !empty($_POST['revenue']) ? floatval($_POST['revenue']) : 0;
	$comments = mysqli_real_escape_string($con, trim($_POST['comments']));

	// Validate required fields
	if (empty($name) || empty($phone) || empty($vehicle_number) || empty($vehicle_type) || empty($insurance_company) || empty($policy_type) || empty($policy_issue_date) || empty($policy_start_date) || empty($policy_end_date) || $premium <= 0) {
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

	// Insert new policy
	$insertSql = "INSERT INTO policy (name, phone, vehicle_number, vehicle_type, insurance_company, policy_type, policy_issue_date, policy_start_date, policy_end_date, fc_expiry_date, permit_expiry_date, premium, revenue, created_date, chassiss, comments) VALUES ('$name', '$phone', '$vehicle_number', '$vehicle_type', '$insurance_company', '$policy_type', '$policy_issue_date', '$policy_start_date', '$policy_end_date', '$fc_expiry_date', '$permit_expiry_date', '$premium', '$revenue', NOW(), '$chassiss', '$comments')";

	$result = mysqli_query($con, $insertSql);
	
	if ($result) {
		$policy_id = mysqli_insert_id($con);
		
		
		// inserting data in account 
		
            	
            
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
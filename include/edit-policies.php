<?php
	require 'session.php';
	require 'config.php';

	// Check if policy_id is set (from new modal) or id (from old form)
	$id = isset($_POST['policy_id']) ? $_POST['policy_id'] : $_POST['id'];
	$name = strtoupper(trim($_POST['name']));
	$phone = trim($_POST['phone']);
	$vehicle_number = strtoupper(trim($_POST['vehicle_number']));
	$vehicle_type = $_POST['vehicle_type'];
	$insurance_company = $_POST['insurance_company'];
	$policy_type = $_POST['policy_type'];
	$policy_start_date = date('Y-m-d',strtotime($_POST['policy_start_date']));
	$policy_end_date = date('Y-m-d',strtotime($_POST['policy_end_date']));
	
	// Handle optional fields that are now collected from frontend
	$chassiss = !empty($_POST['chassiss']) ? mysqli_real_escape_string($con, trim($_POST['chassiss'])) : '';
	$policy_issue_date = !empty($_POST['policy_issue_date']) ? date('Y-m-d',strtotime($_POST['policy_issue_date'])) : $policy_start_date;
	$fc_expiry_date = !empty($_POST['fc_expiry_date']) ? date('Y-m-d',strtotime($_POST['fc_expiry_date'])) : null;
	$permit_expiry_date = !empty($_POST['permit_expiry_date']) ? date('Y-m-d',strtotime($_POST['permit_expiry_date'])) : null;
	
	$premium = floatval($_POST['premium']);
	
	// New financial fields
	$payout = !empty($_POST['payout']) ? floatval($_POST['payout']) : null;
	$customer_paid = !empty($_POST['customer_paid']) ? floatval($_POST['customer_paid']) : null;
	$discount = !empty($_POST['discount']) ? floatval($_POST['discount']) : null;
	$calculated_revenue = !empty($_POST['calculated_revenue']) ? floatval($_POST['calculated_revenue']) : null;
	$comments = !empty($_POST['comments']) ? trim($_POST['comments']) : null;
	
	// Use calculated revenue as the revenue value for backward compatibility
	$revenue = $calculated_revenue !== null ? $calculated_revenue : 0;
	
	// Prepare update query with new financial fields
	$update_sql = "UPDATE policy SET 
		name = ?, phone = ?, vehicle_number = ?, vehicle_type = ?, 
		insurance_company = ?, policy_type = ?, policy_issue_date = ?, 
		policy_start_date = ?, policy_end_date = ?, fc_expiry_date = ?, 
		permit_expiry_date = ?, premium = ?, revenue = ?, chassiss = ?,
		payout = ?, customer_paid = ?, discount = ?, calculated_revenue = ?,
		comments = ?, updated_at = NOW()
		WHERE id = ?";
	
	$stmt = $con->prepare($update_sql);
	
	if ($stmt) {
		$stmt->bind_param("sssssssssssdssdddssi", 
			$name, $phone, $vehicle_number, $vehicle_type,
			$insurance_company, $policy_type, $policy_issue_date,
			$policy_start_date, $policy_end_date, $fc_expiry_date,
			$permit_expiry_date, $premium, $revenue, $chassiss,
			$payout, $customer_paid, $discount, $calculated_revenue,
			$comments, $id
		);
		
		$sql_result = $stmt->execute();
		$stmt->close();
	} else {
		// Fallback to old query format for backward compatibility
		$sql = mysqli_query($con, "update policy set name='$name', phone='$phone', vehicle_number='$vehicle_number', vehicle_type='$vehicle_type', insurance_company='$insurance_company', policy_type='$policy_type', policy_issue_date='$policy_issue_date', policy_start_date='$policy_start_date', policy_end_date='$policy_end_date', fc_expiry_date='$fc_expiry_date', permit_expiry_date='$permit_expiry_date', premium='$premium', revenue='$revenue', chassiss='$chassiss' where id='".$id."' ");
		$sql_result = $sql;
	}
		
            // income section is start	
            
            // Include your database connection
            include 'account.php'; // This provides $acc
            
            // Sample or dynamic input values
            $date = date('Y-m-d');
            $description = 'Insurance';
            $category = 'Insurance';
            $subcategory = 'Insurance';
            // Use calculated_revenue if available, otherwise use legacy revenue
            $revenue_for_income = !is_null($calculated_revenue) ? $calculated_revenue : $revenue;
            $amount = $revenue_for_income;
            $received = $revenue_for_income;
            $balance = 0;
            $insurance_id = $id; //$policy_id; // Must be defined before
            //$name = '';   // optional
            //$phone = '';  // optional
            
            // Step 1: Check if this insurance_id exists
            $check = $acc->prepare("SELECT id FROM income WHERE insurance_id = ?");
            $check->bind_param("s", $insurance_id);
            $check->execute();
            $check->store_result();
            
            if ($check->num_rows > 0) {
                // Record exists – perform UPDATE
                $check->bind_result($id);
                $check->fetch();
                
                $update = $acc->prepare("UPDATE income SET 
                    date = ?, name = ?, phone = ?, description = ?, category = ?, subcategory = ?,
                    amount = ?, received = ?, balance = ?, updated_at = NOW() 
                    WHERE insurance_id = ?");
            
                $update->bind_param(
                    "ssssssddds",
                    $date, $name, $phone, $description,
                    $category, $subcategory, $amount, $received, $balance, $insurance_id
                );
            
                if ($update->execute()) {
                    echo "✅ Income record updated for insurance ID: $insurance_id";
                } else {
                    echo "❌ Update error: " . $update->error;
                }
            
                $update->close();
            
            } else {
                // No record – perform INSERT
                $insert = $acc->prepare("INSERT INTO income (
                    date, name, phone, description, category, subcategory,
                    amount, received, balance, created_at, updated_at, insurance_id
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
            
                $insert->bind_param(
                    "ssssssddds",
                    $date, $name, $phone, $description,
                    $category, $subcategory, $amount, $received, $balance, $insurance_id
                );
            
                if ($insert->execute()) {
                    echo "✅ New income record inserted for insurance ID: $insurance_id";
                } else {
                    echo "❌ Insert error: " . $insert->error;
                }
            
                $insert->close();
            }
            
            $check->close();
            $acc->close();
             // income section is end	

            
            if(isset($_POST['policy_end_date']) ){
				// Update history table with new financial fields if they exist
				$history_revenue = !is_null($calculated_revenue) ? $calculated_revenue : $revenue;
				$history_sql = "INSERT INTO history (name, phone, vehicle_number, vehicle_type, insurance_company, policy_type, policy_issue_date, policy_start_date, policy_end_date, fc_expiry_date, permit_expiry_date, premium, revenue, created_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
				$history_stmt = $con->prepare($history_sql);
				if ($history_stmt) {
					$history_stmt->bind_param("ssssssssssssd", $name, $phone, $vehicle_number, $vehicle_type, $insurance_company, $policy_type, $policy_issue_date, $policy_start_date, $policy_end_date, $fc_expiry_date, $permit_expiry_date, $premium, $history_revenue);
					$history_stmt->execute();
					$history_stmt->close();
				} else {
					// Fallback to old query
					mysqli_query($con, "insert into history (name, phone, vehicle_number, vehicle_type, insurance_company, policy_type, policy_issue_date, policy_start_date, policy_end_date, fc_expiry_date, permit_expiry_date, premium, revenue, created_date) values ('$name', '$phone', '$vehicle_number', '$vehicle_type', '$insurance_company', '$policy_type', '$policy_issue_date', '$policy_start_date', '$policy_end_date', '$fc_expiry_date', '$permit_expiry_date', '$premium', '$revenue', now())");
				}
			}
			
            
		$docx =  array();
		foreach(array_filter($_FILES['files']['name']) as $d => $document){
			$file_name = mysqli_real_escape_string($con,$_FILES['files']['name'][$d]);
			$files = time().$file_name;
			$upload_dir = "../assets/uploads/";
			$uploaded_file = $upload_dir.$files;
				
				move_uploaded_file($_FILES['files']['tmp_name'][$d],$uploaded_file);

			$docx[]="('".$id."', '".$files."')";
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

			$rcs[]="('".$id."', '".$rc."')";
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
				$aadhar_filename = 'aadhar_' . $id . '_' . time() . '.' . $file_extension;
				$aadhar_upload_dir = "../assets/uploads/aadhar/";
				
				// Create directory if it doesn't exist
				if(!is_dir($aadhar_upload_dir)){
					mkdir($aadhar_upload_dir, 0755, true);
				}
				
				$aadhar_uploaded_file = $aadhar_upload_dir . $aadhar_filename;
				
				if(move_uploaded_file($aadhar_file['tmp_name'], $aadhar_uploaded_file)){
					// Delete existing aadhar card for this policy
					$delete_old_sql = "DELETE FROM policy_documents WHERE policy_id = '$id' AND document_type = 'aadhar_card'";
					mysqli_query($con, $delete_old_sql);
					
					// Store new file in database
					$aadhar_sql = "INSERT INTO policy_documents (policy_id, document_type, file_name, file_path, created_at) 
								  VALUES ('$id', 'aadhar_card', '$aadhar_filename', 'assets/uploads/aadhar/$aadhar_filename', NOW())";
					mysqli_query($con, $aadhar_sql);
					error_log("✅ Aadhar card updated successfully: $aadhar_filename");
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
				$pan_filename = 'pan_' . $id . '_' . time() . '.' . $file_extension;
				$pan_upload_dir = "../assets/uploads/pan/";
				
				// Create directory if it doesn't exist
				if(!is_dir($pan_upload_dir)){
					mkdir($pan_upload_dir, 0755, true);
				}
				
				$pan_uploaded_file = $pan_upload_dir . $pan_filename;
				
				if(move_uploaded_file($pan_file['tmp_name'], $pan_uploaded_file)){
					// Delete existing pan card for this policy
					$delete_old_sql = "DELETE FROM policy_documents WHERE policy_id = '$id' AND document_type = 'pan_card'";
					mysqli_query($con, $delete_old_sql);
					
					// Store new file in database
					$pan_sql = "INSERT INTO policy_documents (policy_id, document_type, file_name, file_path, created_at) 
							   VALUES ('$id', 'pan_card', '$pan_filename', 'assets/uploads/pan/$pan_filename', NOW())";
					mysqli_query($con, $pan_sql);
					error_log("✅ PAN card updated successfully: $pan_filename");
				} else {
					error_log("❌ Failed to upload PAN card");
				}
			} else {
				error_log("❌ PAN card validation failed - invalid type or size");
			}
		}
		
		

		if(!empty($_POST["comments"])){
			date_default_timezone_set("Asia/Calcutta");
		    $time = date('Y-m-d H:i:s');
			$c_comment=mysqli_query($con, "insert into comments (policy_id,user,  comments, date) values ('".$id."', '".$_SESSION['username']."','".$_POST["comments"]."', '".$time."')");
		}

		if($sql_result){
			echo "<script>alert('Policy Updated successfully')</script>";
			echo "<script>window.location.href='../policies.php';</script>";
		}else{
			echo "<script>alert('Please try again')</script>";
			echo "<script>window.location.href='../policies.php';</script>";
		}

?>
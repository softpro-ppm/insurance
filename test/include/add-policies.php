<?php
	require 'session.php';
	require 'config.php';

	$name = $_POST['name'];
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
	$premium = $_POST['premium'];
	$revenue = $_POST['revenue'];

	$chk = mysqli_query($con, "select * from policy where vehicle_number='".$vehicle_number."'");
	if(mysqli_num_rows($chk) > 0){
		echo "<script>alert('policy is already exist, Please try again with other vehicle number')</script>";
		echo "<script>window.location.href='../add.php';</script>";
	}else{

		$sql = mysqli_query($con, "insert into policy (name, phone, vehicle_number, vehicle_type, insurance_company, policy_type, policy_issue_date, policy_start_date, policy_end_date, fc_expiry_date, permit_expiry_date, premium, revenue, created_date ) values ('$name', '$phone', '$vehicle_number', '$vehicle_type', '$insurance_company', '$policy_type', '$policy_issue_date', '$policy_start_date', '$policy_end_date', '$fc_expiry_date', '$permit_expiry_date', '$premium', '$revenue', now() )");


		$policy_id = $con->insert_id;

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
		
		

		if(!empty($_POST["comments"])){
			date_default_timezone_set("Asia/Calcutta");
		    $time = date('Y-m-d H:i:s');
			$c_comment=mysqli_query($con, "insert into comments (policy_id,user,  comments, date) values ('".$policy_id."', '".$_SESSION['username']."','".$_POST["comments"]."', '".$time."')");
		}

		if($sql){
			echo "<script>alert('Policy Added successfully')</script>";
			echo "<script>window.location.href='../policies.php';</script>";
		}else{
			echo "<script>alert('Please try again')</script>";
			echo "<script>window.location.href='../add.php';</script>";
		}
	}
?>
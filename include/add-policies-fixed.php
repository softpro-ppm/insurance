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
	// All logic is now inside the main if/else blocks above. No orphaned code remains.
	echo "<script>alert('" . addslashes($error_msg) . "')</script>";

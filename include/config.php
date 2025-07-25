<?php
	date_default_timezone_set("Asia/Calcutta");

	// Database configuration
	$host = "localhost";
	$username = "u820431346_newinsurance";
	$password = "Metx@123";
	$database = "u820431346_newinsurance";

	// Legacy connection for existing code
	$con = new mysqli($host, $username, $password, $database);

	if ($con->connect_errno) {
	  echo "Failed to connect to MySQL: " . $con->connect_error;
	  exit();
	}

	// Set charset for security
	$con->set_charset("utf8");

	// Include security classes
	require_once 'secure-db.php';
	require_once 'input-validator.php';
	require_once 'audit-logger.php';
?>
<?php
	date_default_timezone_set("Asia/Calcutta");

	// Database configuration
	$host = "localhost";
	$username = "u820431346_newinsurance";
	$password = "Softpro@123";
	$database = "u820431346_newinsurance";

	// Legacy connection for existing code with error handling
	try {
		$con = new mysqli($host, $username, $password, $database);

		if ($con->connect_errno) {
			// Log the error but don't display it
			error_log("Database connection failed: " . $con->connect_error);
			
			// For AJAX requests, return JSON error
			if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				header('Content-Type: application/json');
				echo json_encode(['success' => false, 'message' => 'Database connection unavailable']);
				exit();
			}
			
			// For regular requests, show a user-friendly message
			die("Service temporarily unavailable. Please try again later.");
		}

		// Set charset for security
		$con->set_charset("utf8");

	} catch (Exception $e) {
		error_log("Database connection exception: " . $e->getMessage());
		
		// For AJAX requests, return JSON error
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			header('Content-Type: application/json');
			echo json_encode(['success' => false, 'message' => 'Database service unavailable']);
			exit();
		}
		
		die("Service temporarily unavailable. Please try again later.");
	}

	// Include security classes if they exist
	if (file_exists(__DIR__ . '/secure-db.php')) {
		require_once 'secure-db.php';
	}
	if (file_exists(__DIR__ . '/input-validator.php')) {
		require_once 'input-validator.php';
	}
	if (file_exists(__DIR__ . '/audit-logger.php')) {
		require_once 'audit-logger.php';
	}
?>
<?php
	
	require 'session.php';
	require 'config.php';

	// Enable error reporting for debugging
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	if (!isset($_POST['id']) || empty($_POST['id'])) {
		echo "Error: No policy ID provided";
		exit;
	}

	$id = mysqli_real_escape_string($con, $_POST['id']);

	try {
		// Start transaction
		mysqli_autocommit($con, FALSE);

		// First, get policy details to find associated files
		$sql_get_policy = "SELECT * FROM policy WHERE id = '$id'";
		$result = mysqli_query($con, $sql_get_policy);
		
		if (!$result || mysqli_num_rows($result) == 0) {
			throw new Exception("Policy not found");
		}
		
		$policy = mysqli_fetch_assoc($result);
		
		// Define upload directories
		$upload_dirs = [
			'../assets/uploads/aadhar/',
			'../assets/uploads/pan/',
			'../assets/uploads/policy/',
			'../assets/uploads/rc/',
			'../assets/uploads/',
			'uploads/',
			'assets/uploads/'
		];
		
		// File patterns to look for (using policy ID or vehicle number)
		$file_patterns = [
			$id . '_aadhar.*',
			$id . '_pan.*',
			$id . '_policy.*',
			$id . '_rc.*',
			$policy['vehicle_number'] . '_aadhar.*',
			$policy['vehicle_number'] . '_pan.*',
			$policy['vehicle_number'] . '_policy.*',
			$policy['vehicle_number'] . '_rc.*'
		];
		
		// Delete associated files
		$deleted_files = [];
		foreach ($upload_dirs as $dir) {
			if (is_dir($dir)) {
				foreach ($file_patterns as $pattern) {
					$files = glob($dir . $pattern);
					foreach ($files as $file) {
						if (file_exists($file) && is_file($file)) {
							if (unlink($file)) {
								$deleted_files[] = $file;
							}
						}
					}
				}
			}
		}

		// Delete from policy_documents table
		$sql_delete_documents = "DELETE FROM policy_documents WHERE policy_id = '$id'";
		if (!mysqli_query($con, $sql_delete_documents)) {
			// Don't throw error if table doesn't exist yet
			error_log("Warning: Could not delete from policy_documents table: " . mysqli_error($con));
		}

		// Delete from policy table
		$sql_delete_policy = "DELETE FROM policy WHERE id = '$id'";
		if (!mysqli_query($con, $sql_delete_policy)) {
			throw new Exception("Error deleting policy: " . mysqli_error($con));
		}

		// Delete from income table (if exists)
		if (file_exists('account.php')) {
			include 'account.php';
			if (isset($acc) && $acc) {
				$stmt = $acc->prepare("DELETE FROM income WHERE insurance_id = ?");
				if ($stmt) {
					$stmt->bind_param("s", $id);
					$stmt->execute();
					$stmt->close();
				}
				$acc->close();
			}
		}

		// Commit transaction
		mysqli_commit($con);
		
		// Success response
		$response = [
			'success' => true,
			'message' => 'Policy deleted successfully',
			'deleted_files' => count($deleted_files),
			'files' => $deleted_files
		];
		
		echo json_encode($response);
		
	} catch (Exception $e) {
		// Rollback transaction
		mysqli_rollback($con);
		
		// Error response
		$response = [
			'success' => false,
			'message' => 'Error: ' . $e->getMessage()
		];
		
		echo json_encode($response);
	} finally {
		// Reset autocommit
		mysqli_autocommit($con, TRUE);
		mysqli_close($con);
	}

?>
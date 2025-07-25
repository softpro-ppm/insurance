<?php  
	include 'session.php';
	include 'config.php';

	// Add error reporting for debugging
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	echo "DEBUG: Starting view-policy.php<br>";
	echo "POST ID: " . $_POST['id'] . "<br>";

	$sql = mysqli_query($con, "select * from policy where id='".$_POST['id']."'");
	$r=mysqli_fetch_array($sql);

	if (!$r) {
		echo "ERROR: No policy found with ID " . $_POST['id'];
		exit;
	}

	echo "DEBUG: Policy found - " . $r['vehicle_number'] . "<br>";

	$data = '<div class="modal-header bg-gradient-primary text-white border-0">
            <h5 class="modal-title">Policy Details - <strong>'.$r['vehicle_number'].'</strong></h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
            <h6>Customer: '.$r['name'].'</h6>
            <p>Phone: '.$r['phone'].'</p>
            <p>Vehicle Type: '.$r['vehicle_type'].'</p>
            <p>Premium: '.$r['premium'].'</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>';

	echo $data;
?>

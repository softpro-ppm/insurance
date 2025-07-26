<?php  
	include 'session.php';
	include 'config.php';

	$sql = mysqli_query($con, "select * from policy where id='".$_POST['id']."'");
	$r=mysqli_fetch_array($sql);

	echo "<h3>DEBUG: Policy ID: ".$r['id']." - Vehicle: ".$r['vehicle_number']."</h3>";

	// Debug files
	echo "<h4>Files Debug:</h4>";
	$files_sql = mysqli_query($con, "SELECT * FROM files WHERE policy_id='".$r['id']."' ORDER BY id ASC");
	$file_count = 0;
	echo "SQL Query: SELECT * FROM files WHERE policy_id='".$r['id']."' ORDER BY id ASC<br>";
	echo "Number of rows: " . mysqli_num_rows($files_sql) . "<br>";
	
	while ($file_row = mysqli_fetch_array($files_sql)) {
		$file_count++;
		echo "File {$file_count}: ID={$file_row['id']}, Filename={$file_row['files']}<br>";
	}

	// Debug RC
	echo "<h4>RC Files Debug:</h4>";
	$rc_sql = mysqli_query($con, "SELECT * FROM rc WHERE policy_id='".$r['id']."' ORDER BY id ASC");
	$rc_count = 0;
	echo "SQL Query: SELECT * FROM rc WHERE policy_id='".$r['id']."' ORDER BY id ASC<br>";
	echo "Number of rows: " . mysqli_num_rows($rc_sql) . "<br>";
	
	while ($rc_row = mysqli_fetch_array($rc_sql)) {
		$rc_count++;
		echo "RC File {$rc_count}: ID={$rc_row['id']}, Filename={$rc_row['files']}<br>";
	}

	echo "<h4>Final Counts:</h4>";
	echo "Total Policy Files: $file_count<br>";
	echo "Total RC Files: $rc_count<br>";
?>

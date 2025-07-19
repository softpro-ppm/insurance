<?php
	
	require 'session.php';
	require 'config.php';

	$id = $_POST['id'];

	$sql = mysqli_query($con, "delete from policy where id='".$id."'");
	if($sql){
		echo "Policy delete successfully";
	}else{
		echo "please try again";
	}
?>
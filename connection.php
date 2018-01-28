<?php
	require_once './db_login.php';
	$mysqli = new mysqli($hostname, $username, $password, $database);
	if ($mysqli->error) {
		echo "Error connecting to database! Message: ".$mysqli->error;
	} 
?>
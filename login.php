<?php
	session_start();

	include('./connection.php');

	 if (isset($_POST['submit']) && !isset($_SESSION['loggedin'])) {
	 	$select_query = "SELECT user_id, username, password, first_name, last_name, access_level FROM a6_users";
	 	$select_result = $mysqli->query($select_query);
	 	if ($mysqli->error) {
	 		print "Select query error! Message: ".$mysqli->error;
	 	}

	 	while ($row = $select_result->fetch_object()) {
	 		if ((($_POST['username']) == ($row->username)) && (md5($_POST['password']) == ($row->password))) {
	 			$_SESSION['loggedin'] = true;
	 			$_SESSION['loggedin_user'] = $row->username;
	 			$_SESSION['loggedin_user_id'] = $row->user_id;
	 			$_SESSION['loggedin_user_access'] = $row->access_level;

	 		} else {
	 			
	 		}
	 	}
	 }
	  if (isset($_SESSION['loggedin'])) {
	  	header("Location: admin.php");
	  }
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Reviews! - Login</title>
		<style>
			@import url('./css/reset.css'); 
			@import url('./css/styles.css');
			
		</style>
	</head>
	<body>
		
			<form method="post" action="#">
				<label for="username">Username</label>
				<input name="username" id="username" type="text" /><br />
				<label for="password">Password</label>
				<input name="password" id="password" type="password" /><br />
				<input name="submit" id="submit" type="submit" value="Login">
			</form>
			</body>
</html>
<? $mysqli->close(); ?>
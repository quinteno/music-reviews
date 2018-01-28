<?php
	session_start();

	include('./connection.php');

	if (!isset($_SESSION['loggedin'])) {
		print "<!DOCTYPE html>";
		print "<html>";
		print "<head><meta charset=\"utf-8\"><title>Please login</title></head>";
		print "<body><p>You must be lost. Please <a href=\"./login.php\">login.</a></p></body></html>";
	
	} else {
		if ($_SESSION['loggedin_user_access'] == 'administrator') {
			
			if (isset($_POST['submit'])) {
			$delete_query = "DELETE FROM a5_reviews
							WHERE review_id ='".$_POST['delete_review']."'";
			$delete_result = $mysqli->query($insert_query);
			header('Location: ./admin.php');
			if (!$delete_result) die($mysqli->error);
			} 
		

		
			$select_query = "SELECT review_id, album_name, album_review, album_rating, album_image_url, DATE_FORMAT(review_creation_date, '%M %d, %Y %l:%i%p') as review_creation_date FROM a5_reviews";
			$select_result = $mysqli->query($select_query);
			if (!$select_result) die($mysqli->error);
		}

	
	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Reviews! - Home</title>
		<style>
			@import url('./css/reset.css');
			@import url('./css/styles.css');
		</style>
	</head>
	<body>
		<?php
		print "<p class=\"header\">Logged in as ".$_SESSION['loggedin_user'].".</p>"; ?>
		<p class="header"><a href="./logout.php">Log out</a></p><br />
		<div class="wrapper">
			<a href="./admin.php">admin.php</a>
			<a href="./reviews.php">reviews.php</a>
		<table>
			<tr>
				<td class="head">Album Name</td>
				<td class="head">Album Review</td>
				<td class="head">Album Rating</td>
				<td class="head">Album Artwork</td>
				<td class="head">Review Creation Date</td>	
			</tr>
		<?php
				while ($row = $select_result->fetch_object()) {
					print "<tr>\n";
					/*if ($_SESSION['loggedin_user_access'] == 'administrator') {
						print "<td><a href=\"./delete.php?review_id=".$row->review_id."\">delete</a></td>";
					}*/
					print "<td>".$row->album_name."</td>\n";
					print "<td>".$row->album_review."</td>\n";
					print "<td>".$row->album_rating."</td>\n";
					print "<td><img src=".$row->album_image_url." class=\"album_art\" alt=\"album art\"></td>\n";
					print "<td>".$row->review_creation_date."</td>\n";
					/*if ($_SESSION['loggedin_user_access'] == 'administrator') {
						print "<td>".$row->username."</td>\n";
					}*/
					print "</tr>\n";
				}
		?>
		</table>
		<form method="post" action="./admin.php">
			<p>Do you really want to delete review ID <?php print $_GET['review_id']; ?>?</p>
			<input name="delete_review" id="delete_review" type="hidden" value="<?php print $_GET['review_id']; ?>" /><br />
			<input name="submit" id="yes_btn" type="submit" value="Delete">
			<input name="submit" id="no_btn" type="submit" value="No">
		</form>
	</div>
	</body>
</html>

<?php } $mysqli->close(); ?>


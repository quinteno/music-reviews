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
			$update_query = "UPDATE a5_reviews
							SET album_name = '".$_POST['new_album_name']."',
							 	album_review = '".$_POST['new_album_review']."',
							 	album_rating = '".$_POST['new_album_rating']."',
							 	album_image_url = '".$_POST['new_album_image_url']."'
							 	WHERE review_id = '".$_POST['update_review']."'";
			$update_result = $mysqli->query($update_query);
			header('Location: ./admin.php');
			if (!$update_result) die($mysqli->error);
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
					<?php if ($_SESSION['loggedin_user_access'] == 'administrator') {
						
						print "<td class=\"head\">ID</td>";
					}
					?>
					<td class="head">Album Name</td>
					<td class="head">Album Review</td>
					<td class="head">Album Rating</td>
					<td class="head">Album Artwork</td>
					<td class="head">Review Creation Date</td>
					<?php 
						/*if ($_SESSION['loggedin_user_access'] == 'administrator') {
						print "<td>User</td>";
					}*/
					?>
				</tr>
			<?php
					
					while ($row = $select_result->fetch_object()) {
						print "<tr>\n";
						if ($_SESSION['loggedin_user_access'] == 'administrator') {
							

							print "<td class=\"op\">".$row->review_id."</td>";
						}
						print "<td>".$row->album_name."</td>\n";
						print "<td class=\"review\">".$row->album_review."</td>\n";
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
		<form method="post" action="#">
			<input name="update_review" id="update_review" type="hidden" value="<?php print $_GET['review_id']; ?>" /><br />
			<label for="new_album_name">New Album Name</label>
			<input name="new_album_name" id="new_album_name" type="text"><br />
			<label for="new_album_review">New Album Review</label>
			<input name="new_album_review" id="new_album_review" type="text"><br />
			<label>New Album Rating</label>
			<select name="new_album_rating" id="album_rating">
				<option label=" "></option>
				<option value="1 / 10">1 / 10</option>
				<option value="2 / 10">2 / 10</option>
				<option value="3 / 10">3 / 10</option>
				<option value="4 / 10">4 / 10</option>
				<option value="5 / 10">5 / 10</option>
				<option value="6 / 10">6 / 10</option>
				<option value="7 / 10">7 / 10</option>
				<option value="8 / 10">8 / 10</option>
				<option value="9 / 10">9 / 10</option>
				<option value="10 / 10">10 / 10</option>
			</select><br />
			<label for="new_album_image_url">New Album Image URL</label>
			<input name="new_album_image_url" id="new_album_image_url" type="text">
			<input name="submit" id="submit" type="submit" value="Update">
			
		</form>
		</div>
	</body>
</html>

<?php } $mysqli->close(); ?>
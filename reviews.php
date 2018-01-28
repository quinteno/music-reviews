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
			$select_query = "SELECT review_id, album_name, album_review, album_rating, album_image_url, DATE_FORMAT(review_creation_date, '%M %d, %Y %l:%i%p') as review_creation_date, user_id
							 FROM a6_reviews 
							 ORDER BY album_name";
			$select_result = $mysqli->query($select_query);
			if (!$select_result) die($mysqli->error);
		} elseif ($_SESSION['loggedin_user_access'] == 'reviewer') {
			$select_query = "SELECT r.review_id, r.album_name, r.album_review, r.album_rating, r.album_image_url, DATE_FORMAT(r.review_creation_date, '%M %d, %Y %l:%i%p') as review_creation_date, u.user_id 
							 FROM a6_reviews r, a6_users u 
							 WHERE r.user_id = u.user_id AND u.user_id = '".$_SESSION['loggedin_user_id']."'
							 ORDER BY album_name";
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
						print "\t<tr>\n";
						print "\t\t\t\t\t<td><a href=\"./review.php?review_id=".$row->review_id."\" class=\"review_anchor\">".$row->album_name."</a></td>\n";
						print "\t\t\t\t\t<td><a href=\"./review.php?review_id=".$row->review_id."\" class=\"review_anchor\">".$row->album_review."</a></td>\n";
						print "\t\t\t\t\t<td><a href=\"./review.php?review_id=".$row->review_id."\" class=\"review_anchor\">".$row->album_rating."</a></td>\n";
						print "\t\t\t\t\t<td><a href=\"./review.php?review_id=".$row->review_id."\" class=\"review_anchor\"><img src=".$row->album_image_url." class=\"album_art\" alt=\"album art\"></a></td>\n";
						print "\t\t\t\t\t<td><a href=\"./review.php?review_id=".$row->review_id."\" class=\"review_anchor\">".$row->review_creation_date."</a></td>\n";
						
						
						
						
						
					print "\t</tr>\n";
					}
			?>
			</table>
			
				
			</div>
		
	</body>
</html>

<?php } $mysqli->close(); ?>


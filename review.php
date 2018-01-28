<?php
	session_start();

	include('./connection.php');


	if (!isset($_SESSION['loggedin'])) {
		$select_query = "SELECT * FROM a6_reviews";
		$select_result = $mysqli->query($select_query);
		if(!$select_result) die($mysqli->error);
		
		print "<!DOCTYPE html>";
		print "<html>";
		print "\t<head>";
		print "\t\t\t<meta charset=\"utf-8\">";
		print "\t\t\t<title>Please login</title>";
		print "\t</head>";
		print "\t<body>";
		print "\t\t\t<p>Please <a href=\"./login.php\">login.</a></p>";
		print "\t</body>\n";
		print "</html>";
	} else {
		$select_query = "SELECT review_id, album_name, album_review, album_rating, album_image_url, DATE_FORMAT(review_creation_date, '%M %d, %Y %l:%i%p') as review_creation_date 
							FROM a6_reviews
							WHERE review_id = '".$_GET['review_id']."'";
		$select_result = $mysqli->query($select_query);
		if (!$select_result) die($mysqli->error);

		if (!empty($_POST['cmt']) && isset($_POST['submit']) && $_POST['submit'] == "Submit") {
			$insert_query = "INSERT INTO a6_comments(user_id, review_id, comment_id, comment_creation_date, comment)
							 VALUES ('".$_SESSION['loggedin_user_id']."', '".$_GET['review_id']."', NULL, CURRENT_TIMESTAMP, '".$_POST['cmt']."')";
			$insert_result = $mysqli->query($insert_query);	
			
			if(!$insert_result) {
				die($mysqli->error);
			} else {
				header('Location: ./reviews.php');
			}
		}

			$comment_select_query = "SELECT comment_id, DATE_FORMAT(comment_creation_date, '%M %d, %Y %l:%i%p') as comment_creation_date, comment, user_id, review_id
							  FROM a6_comments
							  WHERE review_id  = '".$_GET['review_id']."'";
			$comment_select_result = $mysqli->query($comment_select_query);
			if(!$comment_select_result) die($mysqli->error);
			
		

		
			


	
	
?>
<?php 
				//validation
				$cmtErr = "";

				function test_input($input) {
					$input = trim($input);
					$input = stripslashes($input);
					$input = htmlspecialchars($input);
					return $input;
			}
			
					if (isset($_POST['submit']) && $_POST['submit'] == "Submit" && $_SERVER["REQUEST_METHOD"] == "POST") {
						if (empty($_POST['cmt'])) {
							$cmtErr = "Field is required.";
						} else {
							$cmt = test_input($_POST['cmt']);
						}
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
							
							print "\t\t\t\t\t<td>".$row->album_name."</td>\n";
							print "\t\t\t\t\t<td class=\"review\">".$row->album_review."</td>\n";
							print "\t\t\t\t\t<td>".$row->album_rating."</td>\n";
							print "\t\t\t\t\t<td><img src=".$row->album_image_url." class=\"album_art\" alt=\"album art\"></td>\n";
							print "\t\t\t\t\t<td>".$row->review_creation_date."</td>\n";
							
							
							
							
							
						print "\t</tr>\n";
						}
					
			?>
			</table>
			<div id="cmts">
					<table>
						<tr>
							<td class="cmt-head">User ID</td>
							<td class="cmt-head">Comment</td>
							<td class="cmt-head">Creation Date</td>
						</tr>
						
					
						
						<?php 
						while ($row = $comment_select_result->fetch_object()) {
							print "\t<tr>\n";
							print "\t\t\t\t\t<td>".$row->user_id."</td>\n";
							print "\t\t\t\t\t<td>".$row->comment."</td>\n";
							print "\t\t\t\t\t<td>".$row->comment_creation_date."</td>\n";
							print "\t</tr>\n";
						} 
						?>
					</table>
					<form method="post" action="#">
							
							<label for="cmt">Comment </label>
							<input name="cmt" id="cmt" type="text"><span class="error"><?php echo $cmtErr; ?></span><br /><br />
							<input name="submit" id="submit" type="submit" value="Submit"><br />
					</form>
				</div>
			</div>
		
	</body>
</html>

<?php } $mysqli->close(); ?>

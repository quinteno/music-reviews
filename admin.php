<?php
	session_start();

	include('./connection.php');

		if($_SERVER["REQUEST_METHOD"] == "POST") {

						if (!empty($_POST['album_name'])) {
							setcookie("album_name", $_POST['album_name'], time()+5);
						}
						if (!empty($_POST['album_review'])) {
							setcookie("album_review", $_POST['album_review'], time()+5);
						}
						
						if (!empty($_POST['album_image_url'])) {
							setcookie("album_image_url", $_POST['album_image_url'], time()+5);
						}
						
							
					} else {

					}

	if (!isset($_SESSION['loggedin'])) {
		print "<!DOCTYPE html>";
		print "<html>";
		print "<head><meta charset=\"utf-8\"><title>Please login</title></head>";
		print "<body><p>You must be lost. Please <a href=\"./login.php\">login.</a></p></body></html>";

	} else {
			
			if (!empty($_POST['album_name']) && !empty($_POST['album_review']) && !empty($_POST['album_rating']) && !empty($_POST['album_image_url']) && (isset($_POST['submit']) && $_POST['submit'] == "Submit")) {
			$insert_query = "INSERT INTO a6_reviews(review_id, album_name, album_review, album_rating, album_image_url, review_creation_date, user_id)
							 VALUES (NULL, '".$_POST['album_name']."', '".$_POST['album_review']."', '".$_POST['album_rating']."', '".$_POST['album_image_url']."', CURRENT_TIMESTAMP, '".$_SESSION['loggedin_user_id']."')";
			$insert_result = $mysqli->query($insert_query);
			if (!$insert_result) die($mysqli->error);
			//xml shit here
			
			$xml_review_id_query = "SELECT review_id, review_creation_date FROM a6_reviews ORDER BY review_creation_date  LIMIT 1";
			$xml_review_id_result = $mysqli->query($xml_review_id_query);


			//$review_id = "";
			while ($row = $xml_review_id_result->fetch_object()) {
				 $review_id = $row->review_id;

			}
			echo $review_id;
	   			$xmlFile = './reviews.xml';
	  			$str = file_get_contents($xmlFile);
	   			$xml = new SimpleXMLElement($str);

	   			$title = $_POST['album_name'];
	   			$description = $_POST['album_review'];
	   			$link = "<a href=\"./review.php?review_id=\"".$review_id."\">Direct link</a>";
	   			

	   			$title = htmlentities($title, ENT_COMPAT, 'UTF-8', false);
	   			$description = htmlentities($description, ENT_COMPAT, 'UTF-8', false);
	   			

	   			$newItem = $xml->channel->addChild("review", "");

	   			$newItem->addChild("title", $title);
	   			$newItem->addChild("description", $description);
	   			$newItem->addChild("link", $link);
	   			

	   			$xml->asXML($xmlFile);
	   		

			} elseif ((isset($_POST['submit']) && $_POST['submit'] == "Delete")) {
				$delete_query = "DELETE FROM a6_reviews
								 WHERE review_id ='".$_POST['delete_review']."'";
				$delete_result = $mysqli->query($delete_query);
				
				if (!$delete_result) die($mysqli->error);
			} elseif ((isset($_POST['submit']) && $_POST['submit'] == "Edit")) {
			$update_query = "UPDATE a6_reviews
							 SET album_name = '".$_POST['new_album_name']."',
							 	album_review = '".$_POST['new_album_review']."',
							 	album_rating = '".$_POST['new_album_rating']."',
							 	album_image_url = '".$_POST['new_album_image_url']."',
							 	WHERE review_id = '".$_POST['update_review']."'";
			$update_result = $mysqli->query($update_query);
			
			if (!$update_result) die($mysqli->error);
			echo ($_POST['new_album_name']);
			} 
		
		if ($_SESSION['loggedin_user_access'] == 'administrator') {
			$select_query = "SELECT review_id, album_name, album_review, album_rating, album_image_url, DATE_FORMAT(review_creation_date, '%M %d, %Y %l:%i%p') as review_creation_date, user_id
							 FROM a6_reviews";
			$select_result = $mysqli->query($select_query);
			if (!$select_result) die($mysqli->error);
		} elseif ($_SESSION['loggedin_user_access'] == 'reviewer') {
			$select_query = "SELECT review_id, album_name, album_review, album_rating, album_image_url, DATE_FORMAT(review_creation_date, '%M %d, %Y %l:%i%p') as review_creation_date, u.user_id 
							 FROM a6_reviews r, a6_users u
							 WHERE r.user_id = u.user_id AND u.user_id = '".$_SESSION['loggedin_user_id']."'";
							 
			$select_result = $mysqli->query($select_query);
			if (!$select_result) die($mysqli->error);
		}


	
	
?>
<?php 
				//validation and cookies
				$album_nameErr = "";
				$album_reviewErr = "";
				$album_ratingErr = "";
				$album_image_urlErr = "";
				$album_name = "";
				$album_review = "";
				$album_rating = "";
				$album_image_url = "";

				function test_input($input) {
					$input = trim($input);
					$input = stripslashes($input);
					$input = htmlspecialchars($input);
					return $input;
			}
			
					if (isset($_POST['submit']) && $_POST['submit'] == "Submit" && $_SERVER["REQUEST_METHOD"] == "POST") {
						if (empty($_POST['album_name'])) {
							$album_nameErr = "Field is required.";
						} else {
							$album_name = test_input($_POST['album_name']);
						} if (empty($_POST['album_review'])) {
							$album_reviewErr = "Field is required.";
						} else {
							$album_review = test_input($_POST['album_review']);
						} if (empty($_POST['album_rating'])) {
							$album_ratingErr = "Field is required.";
						} else {
							$album_rating = test_input($_POST['album_rating']);
						} if (empty($_POST['album_image_url'])) {
							$album_image_urlErr = "Field is required.";
						} else {
							$album_image_url = test_input($_POST['album_image_url']);
						}
					

					}
	//OOP
	class Name {
		public $cms_t1 = 'Review System!';

		public function title() {
			return $this->cms_t1;
		}
	}

	$cms_title = new Name(); 
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
		
		print "<p class=\"header\">Welcome to the ".$cms_title->title()."</p>";
		print "<p class=\"header\">Logged in as ".$_SESSION['loggedin_user'].".</p>"; ?>
		<p class="header"><a href="./logout.php">Log out</a></p><br />
		<div class="wrapper">
			<a href="./admin.php">admin.php</a>
			<a href="./reviews.php">reviews.php</a>
			
			<table>
				<tr>
					<?php if ($_SESSION['loggedin_user_access'] == 'administrator') {
						print "<td class=\"head\">Operation</td>\n";
						print "\t\t\t\t\t<td class=\"head\">ID</td>\n";
						
					}
					?>
					<td class="head">Album Name</td>
					<td class="head">Album Review</td>
					<td class="head">Album Rating</td>
					<td class="head">Album Artwork</td>
					<td class="head">Review Creation Date</td>
					<td class="head">User ID</td>
					<td class="head">Comments</td>	
				</tr>

			<?php
					
					while ($row = $select_result->fetch_object()) {
						print "\t<tr>\n";
						if ($_SESSION['loggedin_user_access'] == 'administrator') {
							print "\t\t\t\t\t<td class=\"op\"><a href=\"./delete.php?review_id=".$row->review_id."\"><img src=\"./img/delete.png\" alt=\"delete button\" class=\"trash\"></a><a href=\"./update.php?review_id=".$row->review_id."\"><img src=\"./img/edit.png\" alt=\"edit review\" class=\"edit\"></a></td>";
							print "\t\t\t\t\t<td class=\"op\">".$row->review_id."</td>";
							
						}
						print "\t\t\t\t\t<td>".$row->album_name."</td>\n";
						print "\t\t\t\t\t<td class=\"review\">".$row->album_review."</td>\n";
						print "\t\t\t\t\t<td>".$row->album_rating."</td>\n";
						print "\t\t\t\t\t<td><img src=".$row->album_image_url." class=\"album_art\" alt=\"album art\"></td>\n";
						print "\t\t\t\t\t<td>".$row->review_creation_date."</td>\n";
						
						print "\t\t\t\t\t<td>".$row->user_id."</td>\n";
						print "\t\t\t\t\t<td><a href=\"./review.php?review_id=".$row->review_id."\">View Comments</a></td>\n";
						
						
						
					print "\t</tr>\n";
					}
			?>
			</table>
			 <input name="xml_review_id" id="xml_review_id" type="hidden" value="<?php print_r (end($review_id)); ?>" />
				<form method="post" action="#">
					
					<label for="album_name">Album Name and Artist</label>
					<input name="album_name" id="album_name" type="text" value="<?php if (isset($_COOKIE['album_name'])) {
																							print $_COOKIE['album_name'];
																					} ?>"><span class="error"><?php echo $album_nameErr; ?></span><br /><br />
																					
																					
					<label for="album_review">Review</label>
					<input name="album_review" id="album_review" type="text" value="<?php if (isset($_COOKIE['album_review'])) {
																								print $_COOKIE['album_review'];
																					} ?>"><span class="error"><?php echo $album_reviewErr; ?></span><br /><br />
					
					<label for="album_rating">Rating</label>
					<select name="album_rating" id="album_rating">
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
					</select><span class="error"><?php echo $album_ratingErr; ?></span><br /><br />
					
					<label for="album_image_url">Album Art URL</label>
					<input name="album_image_url" id="album_image_url" type="text" value="<? if (isset($_COOKIE['album_image_url'])) {
																									print $_COOKIE['album_image_url'];
																					} ?>"><span class="error"><?php echo $album_image_urlErr; ?></span><br /><br />
					<input name="submit" id="submit" type="submit" value="Submit"><br />
				</form>
			</div>
		
	</body>
</html>

<?php } $mysqli->close(); ?>


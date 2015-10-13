<?php
	require_once('wall_process.php');

	date_default_timezone_set('America/Los_Angeles');
	//var_dump($_SESSION);
?>
<html>
	<head>
		<title>Login and Registration</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<div class="container1">
			<div class="header">
				<h1>CodingDojo Wall</h1>
				<?= "<p>Welcome {$_SESSION['first_name']}!</p>" ?>
				<form class="logout" action="process.php" method="post">
					<input type="hidden" name="action" value="logout">
					<input type="submit" name="logout" value="Logout!">
				</form>
			</div>
			<div class="posts">
				<h2>Post a message</h2>
				<form action="wall_process.php" method="post">
					<textarea class="post" name="post"></textarea>
					<input type="hidden" name="action" value="post_message">
					<input class="button" type="submit" name="post_message" value="Post a message">
				</form>
				<?php
					get_all_messages();
					foreach($_SESSION["messages"] as $message)
					{
						$str_to_time = strtotime($message["created_at"]);
						$date = date("F jS Y", $str_to_time);
						get_all_comments($message['message_id']);

						// display messages
						echo "<p class='bold'>{$message['first_name']} {$message['last_name']} - $date</p>";

						// display delete button if the message has been here less than 30 mins.
						// bug in this where if you don't refresh the page after 30min and the delete
						// button is still there then you can still delete, fix: add same check inside
						// wall_process.php to not allow deleting of row in DB if over 30min.
						if(round(abs(time() - $str_to_time) / 60) < 30)
						{
							// Delete message button
							echo "<form class='inline' action='wall_process.php' method='post'>";
							echo "<input type='hidden' name='action' value='delete_message'>";
							echo "<input type='hidden' name='message_id' value='{$message['message_id']}'>";
							echo "<input type='submit' name='delete_message' value='Delete'>";
							echo "</form>";
						}

						// display messages (cont.)
						echo "<div class='indent'>";
						echo "<p>{$message['message']}</p>";
						echo "</div>";

						foreach($_SESSION["comments"] as $comment)
						{
							$str_to_time2 = strtotime($comment['created_at']);
							$date2 = date("F jS Y", $str_to_time2);

							// display comments for each message
							echo "<div class='indent2'>";
							echo "<p class='bold'>{$comment['first_name']} {$comment['last_name']} - $date2</p>";
							echo "<p>{$comment['comment']}</p>";
							echo "</div>";
						}
						unset($_SESSION["comments"]);

						// display comment text box
						echo "<h4>Post a comment</h4>";
						echo "<form action='wall_process.php' method='post'>";
						echo "<textarea class='comment' name='comment'></textarea>";
						echo "<input type='hidden' name='action' value='post_comment'>";
						echo "<input type='hidden' name='message_id' value='{$message['message_id']}'>";
						echo "<input class='button' type='submit' name='post_comment' value='Post a comment'>";
						echo "</form>";
					}
					unset($_SESSION["messages"]);
				?>
			</div>
		</div>
	</body>
</html>
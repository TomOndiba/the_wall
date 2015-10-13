<?php
	session_start();
	require_once('connection.php');

	if(isset($_POST["action"]) && $_POST["action"] == "post_message")
	{
		if(!empty($_POST["post"]))
		{
			post_message();
		}
		else
		{
			header("Location: wall.php");
		}
	}
	else if(isset($_POST["action"]) && $_POST["action"] == "post_comment")
	{
		if(!empty($_POST["comment"]))
		{
			post_comments();
		}
		else
		{
			header("Location: wall.php");
		}
	}
	else if(isset($_POST["action"]) && $_POST["action"] == "delete_message")
	{
		delete_message();
	}

	function post_message()
	{
		$post = escape_this_string($_POST["post"]);

		$query = "INSERT INTO messages (message, created_at, updated_at, user_id)
				  VALUES ('{$post}', NOW(), NOW(), {$_SESSION['user_id']})";


		if(run_mysql_query($query))
		{
			header("Location: wall.php");
		}
	}

	function delete_message()
	{
		$query = "DELETE FROM messages
				  WHERE id = {$_POST['message_id']}";

		run_mysql_query($query);
		header("Location: wall.php");
	}

	function post_comments()
	{
		$comment = escape_this_string($_POST["comment"]);

		$query = "INSERT INTO comments (comment, created_at, updated_at, message_id, user_id)
				  VALUES ('{$comment}', NOW(), NOW(), '{$_POST['message_id']}', '{$_SESSION['user_id']}')";
		
		if(run_mysql_query($query))
		{
			header("Location: wall.php");
		}
	}

	function get_all_messages()
	{
		$query = "SELECT message,
				  messages.id AS message_id,
				  messages.created_at AS created_at,
				  users.first_name AS first_name,
				  users.last_name AS last_name
				  FROM messages
				  LEFT JOIN users
				  ON messages.user_id = users.id
				  ORDER BY messages.created_at DESC";

		$messages = fetch_all($query);

		if(count($messages) > 0)
		{
			$_SESSION["messages"] = $messages;
		}
		else
		{
			$_SESSION["messages"] = array();
		}
	}

	function get_all_comments($message_id)
	{
		$query = "SELECT comment,
				  users.first_name AS first_name,
				  users.last_name AS last_name,
				  comments.created_at AS created_at
				  FROM comments
				  LEFT JOIN messages
				  ON comments.message_id = messages.id
				  LEFT JOIN users
				  ON comments.user_id = users.id
				  WHERE message_id = '$message_id'";

		$comments = fetch_all($query);

		if(count($comments) > 0)
		{
			$_SESSION["comments"] = $comments;
		}
		else
		{
			$_SESSION["comments"] = array();
		}
	}
?>
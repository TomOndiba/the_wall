<?php
	session_start();
	require_once('connection.php');

	if(isset($_POST["action"]) && $_POST["action"] == "register")
	{
		register_user($_POST);
	}
	else if(isset($_POST["action"]) && $_POST["action"] == "login")
	{
		login_user($_POST);
	}
	else if(isset($_POST["action"]) && $_POST["action"] == "logout")
	{
		session_destroy();
		header("Location: index.php");
		die();
	}

	function register_user($post)
	{
		$errors = array();

		// Begin validation checks
		if(empty($_POST["first_name"]))
		{
			$errors[] = "Please provide a first name!";
		}
		else if(preg_match("/[0-9]/", $_POST["first_name"]))
		{
			$errors[] = "Please provide a valid first name!";
		}

		if(empty($_POST["last_name"]))
		{
			$errors[] = "Please provide a last name!";
		}
		else if(preg_match("/[0-9]/", $_POST["last_name"]))
		{
			$errors[] = "Please provide a valid last name!";
		}

		if(empty($_POST["email"]))
		{
			$errors[] = "Please provide an email!";
		}
		else if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
		{
			$errors[] = "Please provide a valid email!";
		}

		if(empty($_POST["password"]))
		{
			$errors[] = "Please provide a password!";
		}
		else if(strlen($_POST["password"]) < 7)
		{
			$errors[] = "Please provide a password that has at least 6 characters!";
		}

		if(empty($_POST["c_password"]))
		{
			$errors[] = "Please confirm password";
		}
		else if($_POST["password"] != $_POST["c_password"])
		{
			$errors[] = "Please provide a matching password!";
		}
		// End of validation checks

		if(count($errors) > 0)
		{
			$_SESSION["errors"] = $errors;
			header("Location: index.php");
			die();
		}
		else
		{
			$first_name = escape_this_string($_POST["first_name"]);
			$last_name = escape_this_string($_POST["last_name"]);
			$email = escape_this_string($_POST["email"]);
			$password = escape_this_string($_POST["password"]);

			$query = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at)
					  VALUES ('{$first_name}', '{$last_name}', '{$email}', '{$password}', NOW(), NOW())";

			if(run_mysql_query($query))
			{
				$_SESSION["success"] = "You have successfully registered!";
				header("Location: index.php");
				die();
			}
		}
	}

	function login_user($post)
	{
		$email = escape_this_string($_POST["email"]);
		$password = escape_this_string($_POST["password"]);

		$query = "SELECT * FROM users
				  WHERE users.password = '{$password}'
				  AND users.email = '{$email}'";

		$user = fetch_all($query);
		if(count($user) > 0)
		{
			$_SESSION["user_id"] = $user[0]["id"];
			$_SESSION["first_name"] = $user[0]["first_name"];
			$_SESSION["logged_in"] = TRUE;
			header("Location: wall.php");
			die();
		}
		else
		{
			$_SESSION["errors"][] = "Can't find a user with those credentials!";
			header("Location: index.php");
			die();
		}
	}
?>
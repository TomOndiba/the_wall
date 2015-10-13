<?php
	session_start();
	//$_SESSION = array();
	//var_dump($_SESSION);

	if(isset($_SESSION["errors"]))
	{
		foreach($_SESSION["errors"] as $error)
		{
			echo "<p class='error'>{$error}</p>";
		}
		unset($_SESSION["errors"]);
	}
	else if(isset($_SESSION["success"]))
	{
		echo "<p>{$_SESSION['success']}</p>";
		unset($_SESSION["success"]);
	}
?>
<html>
	<head>
		<title>Login and Registration</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<div class="container">
			<h2>Register</h2>
			<form action="process.php" method="post">
				<div class="field">
					<p class="description">First name:</p>
					<input type="text" name="first_name">
				</div>
				<div class="field">
					<p class="description">Last name:</p>
					<input type="text" name="last_name">
				</div>
				<div class="field">
					<p class="description">Email:</p>
					<input type="text" name="email">
				</div>
				<div class="field">
					<p class="description">Password:</p>
					<input type="password" name="password">
				</div>
				<div class="field">
					<p class="description">Confirm Password:</p>
					<input type="password" name="c_password">
				</div>
				<input type="hidden" name="action" value="register">
				<input type="submit" name="register" value="Register!">
			</form>
			<h2>Login</h2>
			<form action="process.php" method="post">
				<div class="field">
					<p class="description">Email:</p>
					<input type="text" name="email">
				</div>
				<div class="field">
					<p class="description">Password:</p>
					<input type="password" name="password">
				</div>
				<input type="hidden" name="action" value="login">
				<input type="submit" name="login" value="Login!">
			</form>
		</div>
	</body>
</html>

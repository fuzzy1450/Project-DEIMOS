<?php
session_start();

include "../php/loginTimeCheck.php";

if(isset($_SESSION["login"])){ #if the user is already logged in, send them to the home page
	header('Location: ../home');
}
?>

<html>
	<head>
		<script src="../js/jquery-3.3.1.min.js"></script>
	</head>
	<body>
		<form autocomplete="on">
			<p>Email: <input type="text" name="UserName" autocomplete="username"></p>
			<p>Password: <input type="password" name="Password" autocomplete="current-password"></p><br>
			<input type="button" value="Login">
		</form>
		
		<script src="login.js"></script>
		
	</body>
</html>

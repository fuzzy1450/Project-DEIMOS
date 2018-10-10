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
		<form autocomplete="off">
			<p>Email: <input type="text" name="UserName" autocomplete="username"></p>
			<p>Password: <input type="password" name="Password" autocomplete="current-password"></p>
			<p>Re-Enter Password: <input type="password" name="PasswordCheck" autocomplete="current-password"></p><br>
			
			<input type="button" value="Sign Up">
		</form>
		
		<script src="register.js"></script>
		
	</body>
</html>

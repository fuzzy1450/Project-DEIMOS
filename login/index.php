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
		<link rel="stylesheet" type="text/css" href="../theme/theme.css"/>
	</head>
	<body class="background">
		<div class="mainContainer centered-hori">
			<h1> Login </h1>
			
			<br><br>
			<form autocomplete="on">
				<p>Email: <input type="text" name="UserName" autocomplete="username"></p>
				<p>Password: <input type="password" name="Password" autocomplete="current-password"></p><br>
				<input type="button" value="Login">
			</form>
		</div>
		<script src="login.js"></script>
		
	</body>
</html>

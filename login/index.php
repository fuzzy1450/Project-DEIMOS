<?php
session_start();

if(isset($_SESSION["login"])){
	if(((time()-(60*60*24)) > $_SESSION["LoginExpire"])){
		
		# delete session if a day has passed since it started
		if(isset( $_COOKIE[session_name()])){
			setcookie( session_name(), "", time()-3600, "/" );
		}
		$_SESSION = array();
		session_destroy();
		session_start();
		
	} else {
		# send user to logged in page 
		header('Location: submitNew.php');
	}
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
			<input type="button" value="Submit">
		</form>
		
		<script src="login.js"></script>
		
	</body>
</html>
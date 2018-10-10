<?php

#check login timer for user. If the current date is past the date represented by the session variable "LoginExpire", destroy the session and start a new one
if(isset($_SESSION["login"])){
	if((time() > $_SESSION["LoginExpire"])){
		
		if(isset( $_COOKIE[session_name()])){
			setcookie( session_name(), "", time()-3600, "/" );
		}
		$_SESSION = array();
		session_destroy();
		session_start();
		
	}
}

?>

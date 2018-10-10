<?php
session_start();
include "loginTimeCheck.php";
include "mySQLVariables.php";

if(!isset($_SESSION["login"])){
	die("Log in to access this page's functions.");
}

$conn = new mysqli($sqlServer, $sqlUsername, $sqlPassword, $dbname);
if (mysqli_connect_errno()) {
	$result = false;
	$errors = 'Failed to connect to login database';
	die(json_encode(array("success"=>$result, "message"=>$errors)));
}

/**
 * Gets a list of games that are currently supported by the system
 * @return mixed[] array of arrays that represent game data
 */
function getGameList(){
	include "mySQLVariables.php";
	$conn = new mysqli($sqlServer, $sqlUsername, $sqlPassword, $dbname);
	if (mysqli_connect_errno()) {
		$result = false;
		$errors = 'Failed to connect to login database';
		die(json_encode(array("success"=>$result, "message"=>$errors)));
	}

	$stmt = $conn->prepare("SELECT * FROM `gamesystems` WHERE 1");
	$stmt->execute();
	$QueryResult = $stmt->get_result();
	
	return $QueryResult->fetch_all();
}


?>

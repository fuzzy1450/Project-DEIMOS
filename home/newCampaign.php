<?php
session_start();

include "../php/loginTimeCheck.php";
include "../php/mySQLVariables.php";
include "../php/campaignAccessManager.php";
include "../php/gameSystemManager.php";

if(!isset($_SESSION["login"])){
	header('Location: ../');
}

$conn = new mysqli($sqlServer, $sqlUsername, $sqlPassword, $dbname);
if (mysqli_connect_errno()) {
	$result = false;
	$errors = 'Failed to connect to login database';
	die(json_encode(array("success"=>$result, "message"=>$errors)));
}

?>

<html>
<head>
	<title>Project Deimos</title>
	<link rel="stylesheet" type="text/css" href="../theme/theme.css"/>
	<script src="../js/jquery-3.3.1.min.js"></script>
</head>

<body class="background">
	<div class="mainContainer centered-hori">
		<h1> Create a New Campaign </h1>
		
		<form autocomplete="off">
				<p>Campaign Name: <input type="text" name="CampaignName"></p>
				<p>Description: <input type="text" name="Description"></p>
				<p class="subtext">(both of these can be changed at any time)</p>
				<p>What Game will you be using for this campaign?: <select>
				<option value = "default"/>
				<?php
					#Get list of games currently supported by the system
					$Games = getGameNameIDs();
					
					foreach($Games as $game){
						echo "<option value = " . $game[0] . ">" . $game[1] . "</option>";
					}
				?>
				</select>
				
				<br><br><br>
				
				<input type="button" value="Create Campaign">
			</form>
		
		<script src="newCampaign.js"></script>
	</div>
</body>

</html>
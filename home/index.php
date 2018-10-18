<?php
session_start();

include "../php/loginTimeCheck.php";
include("../php/mySQLVariables.php");
include("../php/campaignAccessManager.php");

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
</head>

<body class="background">
<div class = "mainContainer foreground centered-hori">
	<div id="campaignList">
		<?php
		 # Get the list of campaigns that the user has access to
		 $CampaignIDs = getCampaignsList($_SESSION['loginID']);
		 foreach($CampaignIDs as $CampaignID){
			 $CampaignDetails = getCampaignDetails($CampaignID);
			 
			 echo "<a href ='../campaign?id=".$CampaignDetails["ID"]."'><div class = 'campaignOption centered-hori'>";
			 echo "<h2 class = 'campaignName'>" . $CampaignDetails["campaignName"] . "</h2>";
			 echo "<p class = 'campaignDescription'>" . $CampaignDetails["Description"] . "</p>";
			 echo "</div></a>";
		 }
		 
		 
		?>

	</div>
	
	<br><br><br>
	<a href="NewCampaign.php"><button>Start a New Campaign</button></a>
</div>


</body>

</html>

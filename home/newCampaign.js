$(document).ready(function() {
	$("input[type=button]").click(function () {
		var CampaignName = $('input[name=CampaignName]').val();
		var gameSys = $('select').val();
		var Desc = $('input[name=Description]').val();
		
		if(gameSys != "default"){
		
			$.ajax({
				type: "POST",
				url: "createCampaign.php",
				data : encodeURI("CampaignName="+CampaignName+"&Description="+Desc+"&gameSys="+gameSys),
				dataType: "json",
				success: function (data) {
					var success = data['success'];
					if(success == false){
						error = data['message'];
						console.log("oh no");
						// TODO error handle when things dont go right
					}
					if(success == true) {
						console.log("oh yea");
					//	window.location.href="./" //send the user back to /home
					}
				}

			})    
		} else {
			//TODO tell the user that they have to choose a game system
		}
	})
})

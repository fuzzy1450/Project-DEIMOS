$(document).ready(function() {
	$("input[type=button]").click(function () {
		var username = $('input[name=UserName]').val();
		var password = $('input[name=Password]').val();
		$.ajax({
			type: "POST",
			url: "login.php",
			data : "UserName="+username+"&Password="+password,
			dataType: "json",
			success: function (data) {
				var success = data['success'];
				if(success == false){
					error = data['message'];
					console.log("oh no");
				}
				if(success == true) {
					console.log("oh yea");
					window.location.href="./submitNew.php"
				}
			}

		})             
	})
})
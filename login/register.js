$(document).ready(function() {
	$("input[type=button]").click(function () {
		var username = $('input[name=UserName]').val();
		var password = $('input[name=Password]').val();
		var pswCheck = $('input[name=PasswordCheck]').val();
		
		if(password === pswCheck){
		
			$.ajax({
				type: "POST",
				url: "CreateAccount.php",
				data : "UserName="+username+"&Password="+password,
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
						window.location.href="../home"
						//TODO log user into new account after registering. perhaps an ajax call within this function?
					}
				}

			})    
		} else {
			//TODO tell the user that the passwords do not match
			console.log("no sir, i dont like it")
		}
	})
})

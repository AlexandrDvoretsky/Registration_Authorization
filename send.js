$(document).ready(function(){
	
	$("#reg").submit(function(){
		$.ajax({
			url: "regs.php",
			type: "POST",
			dataType: "json", 
			data: $(this).serialize(),
			success: function(response) 
				{
					if(response.result == "ok")
					{
						var textOK = "";
						alert("Вы успешно зарегистрировались!");
						$('.errorReg').html(textOK);
						jQuery('#reg')[0].reset();
						window.location.reload(true);
					}
					else
					{
						var textErrors = "";
						for (var key in response.errors) 
						{ 
							textErrors +="<br>"+response.errors[key]+"<br>";
						}
						$('.errorReg').html(textErrors);
					}
				}
		});
		return false;
	});
	
	
	$("#auth").submit(function(){
		$.ajax({
			url: "regs.php",
			type: "POST",
			dataType: "json",
			data: $(this).serialize(),
			success: function(response) 
				{	
					if(response.result == "ok")
					{
						var textOK = "";
						alert("Вы авторизовались!");
						$('.errorAuth').html(textOK);
						jQuery('#auth')[0].reset();
						window.location.reload(true);
					}
					else
					{
						var textErrors = "";
						for (var key in response.errors)
						{ 
							textErrors +="<br>"+response.errors[key]+"<br>";
						}
						$('.errorAuth').html(textErrors);
					}
				}
		});
		return false;
	});
	
	
		$("#logout").submit(function(){
		$.ajax({
			url: "regs.php",
			type: "POST", 
			dataType: "json", 
			data: $(this).serialize(),  
			success: function(response) 
				{
					if(response.result == "ok")
					{	
						window.location.reload(true);
					}
					else
					{
						alert("Вы вошли как гость!");
					}
				}
		});
		return false;
	});
	
	
});

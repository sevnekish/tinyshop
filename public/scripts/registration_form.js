$(document).ready(function(){

	$("input[type=password]").keyup(function(){
		if($("#password1").val() == $("#password2").val() 
							&& $("#password1").val().length != 0 
							&& $("#password2").val().length != 0){
			$("#pwmatch").removeClass("glyphicon-remove");
			$("#pwmatch").addClass("glyphicon-ok");
			$("#pwmatch").css("color","#00A41E");
		}else{
			$("#pwmatch").removeClass("glyphicon-ok");
			$("#pwmatch").addClass("glyphicon-remove");
			$("#pwmatch").css("color","#FF0004");
		}
	});

});
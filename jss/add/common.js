if (!window.halt_onbeforeunload) {
	window.onbeforeunload = function (e) {
		var message = 'Вы точно хотите уйти с этой страницы? Если вы уйдете, то данные в форме добавления будут потеряны.';
		if (typeof e == 'undefined') e = window.event;
		if (e) e.returnValue = message;
		return message;
	}
}

function finish_loading(el) {
	
	el.find("div#add_loader img").hide();
	el.find("div#add_form").slideDown();
	el.find("a.disabled").attr('rel','on');
	el.find("span.arrow").html(' ↑');
	if (el.find("input.password").length == 1) {
		el.find("input.password").parent().prependTo(el.find("div#add_form"));
	}
	
}

$(document).ready(function(){  	

	$(".disabled").live('click', function(event){  
		event.preventDefault();
	});
	
	$('#addform').submit(function() {
		  window.onbeforeunload = null;
	});

	$(".add_meta").click(function(){  
		$(this).parent().append($(this).parent().children("select:last").clone());
		$(this).parent().children(".remove_meta").show();
	});
	
	$(".remove_meta").click(function(){  
		$(this).parent().children("select:last").remove();
		if ($(this).parent().children("select").length < 2) $(this).hide();
	});

}); 

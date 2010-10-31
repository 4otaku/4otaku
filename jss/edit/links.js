$(document).ready(function(){

	$(".arrow-up:first").hide();
	$(".arrow-down:visible:last").hide();
	
	$(".arrow-up").live('click',function(){
		window.LinkValues = new Array();
		$(this).parents("tr.link").find("input, select").each(function(){
			if ($(this).attr('name').split('][').length > 1) {
				window.LinkValues[$(this).attr('name').split('][')[1]] = $(this).val();
				window.LinkIndex = $(this).attr('name').split('][')[0].substr(5, $(this).attr('name').split('][')[0].length - 1);
			}
		});
		$(this).parents("tr.link:visible").prev().find("input, select").each(function(){
			if ($(this).attr('name').split('][').length > 1) {
				ThisName = $(this).attr('name').split('][')[1].substr(0, $(this).attr('name').split('][')[1].length - 1);
				if ($(this).is('input')) $("input[name$=link\\["+window.LinkIndex+"\\]\\["+ThisName+"\\]]").val($(this).val());
				if ($(this).is('select')) $("select[name$=link\\["+window.LinkIndex+"\\]\\["+ThisName+"\\]]").val($(this).val());
				$(this).val(window.LinkValues[$(this).attr('name').split('][')[1]]);
			}
		});
	});
	
	$(".arrow-down").live('click',function(){
		window.LinkValues = new Array();
		$(this).parents("tr.link").find("input, select").each(function(){
			if ($(this).attr('name').split('][').length > 1) {
				window.LinkValues[$(this).attr('name').split('][')[1]] = $(this).val();
				window.LinkIndex = $(this).attr('name').split('][')[0].substr(5, $(this).attr('name').split('][')[0].length - 1);
			}
		});
		$(this).parents("tr.link:visible").next().find("input, select").each(function(){
			if ($(this).attr('name').split('][').length > 1) {
				ThisName = $(this).attr('name').split('][')[1].substr(0, $(this).attr('name').split('][')[1].length - 1);
				if ($(this).is('input')) $("input[name$=link\\["+window.LinkIndex+"\\]\\["+ThisName+"\\]]").val($(this).val());
				if ($(this).is('select')) $("select[name$=link\\["+window.LinkIndex+"\\]\\["+ThisName+"\\]]").val($(this).val());
				$(this).val(window.LinkValues[$(this).attr('name').split('][')[1]]);
			}
		});
	});	
	
});  

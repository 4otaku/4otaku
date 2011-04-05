$(".config_option input, .config_option select").live(
	'change', function() { $.ajax({data: {
		module : 'profile',
		input: true,
		function: 'set_option',
		option_name: $(this).attr('name'),
		option_value: $(this).val()
	}});}
);

$(".logout").live('click', function() { 
	$.cookie('settings', null, {path: '/', domain: window.location.hostname});
	document.location.reload();
});

$(document).ready(function(){
	$(".checked").attr('checked', true);
	$(".not_checked").attr('checked', false);
	$("select .selected").attr('selected', 'selected');	
});

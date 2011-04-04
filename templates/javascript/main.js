$(".config_option input, .config_option select").live(
	'change', function() { $.ajax({data: {
		module : 'profile',
		input: true,
		function: 'set_option',
		option_name: $(this).attr('name'),
		option_value: $(this).val()
	}});}
);

$(".disabled").live('click',function(event){  
	event.preventDefault();
});

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
	$.cookie('beta_settings', null, {path: '/', domain: window.location.hostname});
	$.cookie('beta_settings', null, {path: '/', domain: '.'+window.location.hostname});
	document.location.reload();
});

$(".login_trigger").live('click', function() { 
	$(this).addClass("plaintext");
	$(".login_trigger").not(this).removeClass("plaintext");
	$(".login_part").show().not("."+$(this).attr('href')).hide();
});

$(document).ready(function(){
	$(".checked").attr('checked', true);
	$(".not_checked").attr('checked', false);
	$("select .selected").attr('selected', 'selected');
	
	$("a.with_help").easyTooltip();
	$("a.with_help_art").easyTooltip({
		useAttribute : 'nice_title',
		timeOut: 1000
	});
	
	$(".header_menu_item").hover(
		function(){
			$(".header_menu_item").not(this).removeClass("header_menu_item_hover");
			$(this).addClass("header_menu_item_hover");
		}
	);
	
	$("a.car-toggler").click(function(event){  
		event.preventDefault();
		if ( $(this).attr('rel') == 'closed' ) {
			$(this).attr('rel','open');
			$(this).html('Свернуть все');
			$(".car-monthlisting").slideDown();
		}
		else {
			$(this).attr('rel','closed');
			$(this).html('Развернуть все');
			$(".car-monthlisting").slideUp();
		}
	});
	
	$(".car-yearmonth").click(function(){  
		if ( $(this).attr('rel') == 'closed' ) {
			$(this).attr('rel','open');
			id = $(this).attr('class').split(' ');		
			if (id[2] == 'remember') $.post(window.config.site_dir+"/ajax.php?m=cookie&f=set&field=order."+id[3]+"&val=1");
			$("ul."+id[1]).slideDown();
		}
		else {
			$(this).attr('rel','closed');
			id = $(this).attr('class').split(' ');			
			if (id[2] == 'remember') $.post(window.config.site_dir+"/ajax.php?m=cookie&f=set&field=order."+id[3]+"&val=0");
			$("ul."+id[1]).slideUp();
		}
	});	
});

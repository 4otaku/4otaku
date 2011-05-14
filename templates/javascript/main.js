$(".disabled").live('click',function(event){  
	event.preventDefault();
});

$(".config_option input, .config_option select").live(
	'change', function() { 
		var val; var on_complete;
		
		if ($(this).is(':checkbox')) {
			val = $(this).is(':checked') + 0;
		} else {
			val = $(this).val();
		}
		
		if ($(this).is('.reload')) {
			on_complete = function() {
				document.location.reload();
			};
		} else {
			on_complete = function() {};
		}
		
		$.ajax({data: {
			module : 'profile',
			input: true,
			function: 'set_option',
			option_name: $(this).attr('name'),
			option_value: val,  
			success: on_complete
		}});
	}
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
	
$(".art_size_toggle").live('click', function(event){
	if(is_left_click(event)) {
		var visible = $(".art_toggle:visible");
		var hidden = $(".art_toggle").not(":visible");
		visible.hide();
		hidden.css('display', 'inline-block');
	}
});	

$(".booru_show_full_always").live('click', function(){
// TODO: запилить
});	

$(".art_toggle").live('click', function(event){
	if(is_left_click(event)) {
		if ($(".art_translation").length > 0) {
			if ($(".art_translation:visible").length > 0) {
				$(".art_translation").hide();
			} else {
				$(".art_translation").show();
			}
		}		
	}
});		

$(document).ready(function(){
	$(".checked").attr('checked', true);
	$(".not_checked").attr('checked', false);
	$("select .selected").attr('selected', 'selected');
	
	$(".with_help").easyTooltip();
	$(".with_help_art").easyTooltip({
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
	
	$("a.imageholder, a.similar_navi").hover(function(){
		var imagethumb = $(this).find(".hiddenthumb");
		if (imagethumb.attr('src') == '#') {
			imagethumb.attr('src',imagethumb.attr('rel'));  
		}
		imagethumb.css( {left : ($(this).offset().left + 15) + 'px',top : parseInt($(this).offset().top) - 15 - imagethumb.parent().attr('rel') + 'px'} ).show();
	}, function(){
		$(this).find(".hiddenthumb").hide();
	});		
});

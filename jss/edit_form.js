function get_data() {
	return $("form#edit_post").serialize();
}

$(document).ready(function(){  

	$("input.disabled").click(function(event){  
		event.preventDefault();
	});
	
	$("input.save_changes").unbind('click').click(function(e){
		e.preventDefault();  
		var post = get_data(); 
		var type = $("form#edit_post input[name='type']").val();
		var id = $("form#edit_post input[name='id']").val();
		$("#post-"+id).css({'height':$("#post-"+id).height()});
		$("#post-"+id).html('<table width="100%" height="100%"><tr><td align="center" valign="center"><img src="'+window.config.image_dir+'/ajax-loader.gif"></td></tr></table>');
		$.post(window.config.site_dir+"/ajax.php?m=edit&f=save", post, function(){ 
			if (window.full_reload == true) {
				document.location.reload();
			} else {
				$("#post-"+id).load(window.config.site_dir+"/ajax.php?m=edit&f=show&id="+id+"&type="+type+"&num="+$("input[name='save']").attr('rel')+"&path="+location.pathname ,function(){ 
					$("div.post").css({'height':'auto'}); 
					$(".art_translation").easyTooltip();				
				});
				$("div.edit_field").html(''); $("div.edit_field").hide();
				$('body').css('cursor','default');			
			}
		});

	});
	
	$("input.save_on_enter").keydown(function(e){
		switch (e.which) {
			case 13:
				e.preventDefault();	
				$(this).parents('.edit_wrap').find('input.save_changes').click();
				break;
			default:
				break; 
		}      		
	});	
	
	$("input.drop_changes").click(function(){  
		$("div.edit_field").html('');
		$("div.edit_field").hide();
		window.full_reload = false;
	})

	$(".add_meta").click(function(){  
		$(this).parent().append($(this).parent().children("select:last").clone());
		$(this).parent().children(".remove_meta").show();
	});
	
	$(".remove_meta").click(function(){  
		$(this).parent().children("select:last").remove();
		if ($(this).parent().children("select").length < 2) $(this).hide();
	});
		
	$("div.loader").hide();
	$("div.edit_wrap").parent().show();
});

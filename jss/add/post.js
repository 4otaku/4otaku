String.prototype.replaceall = function(oldstr, newstr) {
	var target = this;
	var check = target.indexOf(oldstr);
	while (check != -1){
		target = target.replace(oldstr,newstr);
		check = target.indexOf(oldstr);
	}
	return(target);
}

$(document).ready(function(){  	

	window.processing_art = 0;
	window.processing_files = 0;	

	$(".remove_link").live('click', function(e){ 
		if ($('.link_'+$(this).attr('rel')+' tr.link').length > 1 || $(this).attr('rel') == 'file') 
			$(this).parents('tr.link').remove();
	});
	
	$(".add_link").click(function(){  
		var oldnum = parseInt($('.link_'+$(this).attr('rel')).children("tr.link:last").attr('rel'));
		var num = oldnum + 1; 
		$('.link_'+$(this).attr('rel')).append('<tr class="link">'+$('.link_'+$(this).attr('rel')).children("tr.link:last").html().replaceall('['+oldnum+']','['+num+']')+'</tr>');
		$('.link_'+$(this).attr('rel')).children("tr.link:last").attr('rel', num);
		if ($(this).attr('rel') == 'main') 
			$('.link_main tr.link:last select').val($('.link_main tr.link:last').prev().find('select').val());
		if ($('.link_'+$(this).attr('rel')).children("tr.link:last img").length) {
			$('.link_'+$(this).attr('rel')).children("tr.link:last input").each(function(){
				$(this).val("");
			});
		}
		$(".arrow-down:hidden").show();
		$(".arrow-down:visible:last").hide();
	});	

	if ($('#post-image').length > 0) image_upload = new qq.FileUploader({
		element: document.getElementById('post-image'),
		action: window.config.site_dir+'/ajax.php?upload=postimage',
		autoSubmit: true,
		onSubmit: function(id, file) {
			$(".processing-image").show();
			$('#error').html('');
			window.processing_art++;
		},
		onComplete: function(id, file, response) {
			window.processing_art = window.processing_art - 1;
			if (window.processing_art == 0) $(".processing-image").hide(); 
			if (response['error'] == 'filetype') {$('#error').html('<b>Ошибка! Выбранный вами файл не является картинкой.</b>');}
			else if (response['error'] == 'maxsize') {$('#error').html('<b>Ошибка! Выбранный вами файл превышает 2 мегабайт.</b>');}
			else {
				$('#transparent td').append('<div style="background-image: url('+response['image']+');"><img class="cancel" src="'+window.config.image_dir+'/cancel.png"><input type="hidden" name="images[]" value="'+response['data']+'"></div>');
				$("#transparent td img.cancel").click(function(){  
					$(this).parent().remove();
				});
			}
		}
	});
	
	$(".image_upload_stop").click(function(){
		image_upload.cancel();
		window.processing_art = 0;
		$(".processing").hide(); 
		$('#error').html('');
	});
	
	$("#transparent td img.cancel").click(function(){  
		$(this).parent().remove();
	}); 		
	
	if ($('#post-file').length > 0) file_upload = new qq.FileUploader({
		element: document.getElementById('post-file'),
		action: window.config.site_dir+'/ajax.php?upload=postfile',
		autoSubmit: true,
		onSubmit: function(id, file) {
			$(".processing-file").show();
			$('#error').html('');
			window.processing_files++;
		},
		onComplete: function(id, file, response) {
			window.processing_files = window.processing_files - 1;
			if (window.processing_files == 0) $(".processing-file").hide(); 
			if (response['error'] == 'maxsize') {$('#error').html('<b>Ошибка! Выбранный вами файл превышает 10 мегабайт.</b>');}
			else {
				var decoded = $('<textarea/>').html(response['data']).val();
				
				if ($('.link_file').children("tr.link:last").length != 0)
					var num = parseInt($('.link_file').children("tr.link:last").attr('rel')) + 1;
				else num = 1;
				if ($('#post-file').attr('rel') == 'add') $('.link_file').append('<tr class="link" rel="0"><td class="input field_name">Прикрепленный файл</td><td class="inputdata">'+decoded.replaceall('[0]','['+num+']')+'</td></tr>');
				else $('.link_file').append('<tr class="link" rel="0"><td colspan="2">'+decoded.replaceall('[0]','['+num+']')+'</td></tr>'); 
				$('.link_file').children("tr.link:last").attr('rel', num);
			}
		}
	});
	
	$(".file_upload_stop").click(function(){
		file_upload.cancel();
		window.processing_art = 0;
		$(".processing-file").hide(); 
		$('#error').html('');
	});
	
}); 

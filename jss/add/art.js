$(document).ready(function(){  	

	window.processing_art = 0;
	
	art_upload = new AjaxUpload('art-image', {
		action: window.config.site_dir+'/ajax.php?upload=art',
		name: 'filedata',
		multiple: true,
		data: {	  },
		autoSubmit: true,
		responseType: 'text/html',
		onSubmit: function(file, extension) {
			$(".processing").show();
			$('#error').html('');
			window.processing_art++;
		},
		onComplete: function(file, response) {
			window.processing_art = window.processing_art - 1;
			if (window.processing_art == 0) $(".processing").hide(); 
			if (response == 'error-filetype') {$('#error').html('<b>Ошибка! Выбранный вами файл не является картинкой.</b>');}
			else if (response == 'error-maxsize') {$('#error').html('<b>Ошибка! Выбранный вами файл превышает 10 мегабайт.</b>');}
			else if (response == 'error-already-have') {$('#error').html('<b>Ошибка! Выбранный вами файл уже есть в базе.</b>');}
			else {
				if ($("#art-image").attr('rel') == 'single') $('#transparent td').html('');
				data = response.split("|");
				$('#transparent td').append('<div style="background-image: url('+data[0]+');"><img class="cancel" src="'+window.config.image_dir+'/cancel.png"><input type="hidden" name="images[]" value="'+data[1]+'"></div>');
				$("#transparent td img.cancel").click(function(){  
					$(this).parent().remove();
				}); 				
			}
		}
	});
	
	$(".art_upload_stop").click(function(){
		art_upload.cancel();
		window.processing_art = 0;
		$(".processing").hide(); 
		$('#error').html('');
	});
	
}); 

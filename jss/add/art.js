function check_similar() {
	var artValues = [];
	$('.art_images input').each(function() {
		artValues.push($(this).val().split("#")[1]);
	});
	$("#is_dublicates").load(window.config.site_dir+"/ajax.php?m=art&f=is_dublicates&data="+artValues.join(","));
}

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
				data = response.split("|");
				if ($("input."+data[1].split("#")[0]).length < 1) {
					if ($("#art-image").attr('rel') == 'single') $('#transparent td').html('');
					$('#transparent td').append('<div style="background-image: url('+data[0]+');"><img class="cancel" src="'+window.config.image_dir+'/cancel.png"><input type="hidden" name="images[]" value="'+data[1]+'" class="'+data[1].split("#")[0]+'"></div>');
					$("#transparent td img.cancel").click(function(){  
						$(this).parent().remove();
						check_similar();
					});
					check_similar();
				}
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

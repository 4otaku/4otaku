$(".thickbox").unbind('click');

$(document).ready(function(){  	

	window.processing_board = 0;
	$("select .selected").attr('selected', 'selected');	
	
	board_upload = new AjaxUpload('board-image', {
		action: window.config.site_dir+'/ajax.php?upload=board',
		name: 'filedata',
		multiple: true,
		data: {	  },
		autoSubmit: true,
		responseType: 'text/html',
		onSubmit: function(file, extension) {
			$(".processing").show();
			$('#error').html('');
			window.processing_board = 1;
		},
		onComplete: function(file, response) {
			window.processing_board = 0;
			$(".processing").hide(); 
			if (response == 'error-filetype') {$('#error').html('<b>Ошибка! Выбранный вами файл не является картинкой.</b>');}
			else if (response == 'error-maxsize') {$('#error').html('<b>Ошибка! Выбранный вами файл превышает 5 мегабайт.</b>');}
			else {
				data = response.split("|");
				$('#transparent td').html('<div style="background-image: url('+data[0]+');"><img class="cancel" src="'+window.config.image_dir+'/images/cancel.png"><input type="hidden" name="image" value="'+data[1]+'"></div>');
				$("#transparent td img.cancel").click(function(){  
					$(this).parent().remove();
				}); 				
			}
		}
	});
	
	$(".board_upload_stop").click(function(){
		board_upload.cancel();
		window.processing_board = 0;
		$(".processing").hide(); 
		$('#error').html('');
	})
	
}); 

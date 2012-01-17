$(document).ready(function(){

	window.processing = 0;

	if ($('#news-image').length > 0) image_upload = new qq.FileUploader({
		element: document.getElementById('news-image'),
		action: window.config.site_dir+'/ajax.php?m=upload&f=news',
		autoSubmit: true,
		onSubmit: function(id, file) {
			$(".processing-image").show();
			$('#error').html('');
			window.processing++;
		},
		onComplete: function(id, file, response) {
			window.processing = window.processing - 1;
			if (window.processing == 0) {
				$(".processing-image").hide();
			}

			if (!response.success) {
				var error = response.data.error;

				if (error == 'filetype') {
					$('#error').html('<b>Ошибка! Выбранный вами файл не является картинкой.</b>');
				} else if (error == 'maxsize') {
					$('#error').html('<b>Ошибка! Выбранный вами файл превышает 2 мегабайт.</b>');
				} else {
					$('#error').html('<b>Неизвестная ошибка.</b>');
				}
			} else {
				response = response.data;

				$('#transparent td').html('<div style="background-image: url('+response['image']+');" class="left right20"><img class="cancel" src="'+window.config.image_dir+'/cancel.png"><input type="hidden" name="image" value="'+response['data']+'"></div>');
				$("#transparent td img.cancel").click(function(){
					$(this).parent().remove();
				});
			}
		}
	});

	$("#transparent td img.cancel").click(function(){
		$(this).parent().remove();
	});
});

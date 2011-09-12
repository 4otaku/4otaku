$(document).ready(function(){  	

	new AjaxUpload('video-file', {
		action: window.config.site_dir+'/ajax.php?upload=video',
		name: 'filedata',
		data: {	  },
		allowedExtensions: ['mp4', 'flv', 'avi'], 
		sizeLimit: 1024*1024*50,
		autoSubmit: true,
		responseType: 'text/html',
		onSubmit: function(file, extension) {
			$(".processing").show();
			$('#error').html('');
		},
		onComplete: function(file, response) {
			$(".processing").hide(); 
			if (response == 'error-filetype') {$('#error').html('<b>Ошибка! Выбранный вами файл не является видео.</b>');}
			else if (response == 'error-maxsize') {$('#error').html('<b>Ошибка! Выбранный вами файл превышает 50 мегабайт.</b>');}
			else if (response == 'error-already-have') {$('#error').html('<b>Ошибка! Выбранный вами файл уже есть в базе.</b>');}
			else {
							
			}
		}
	});
	
}); 

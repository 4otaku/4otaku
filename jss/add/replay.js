$(document).ready(function(){  	

	window.processing_art = 0;
	
	replay = new qq.FileUploader({
		element: document.getElementById('replay_upload'),
		action: window.config.site_dir+'/ajax.php?upload=postfile',
		autoSubmit: true,
		onSubmit: function(id, file) {
			$(".processing").show();
			$('#error').html('');
			window.processing_files++;
		},
		onComplete: function(id, file, response) {
			$(".processing").hide(); 
			if (response['error'] == 'maxsize') {
				$('#error').html('<b>Ошибка! Выбранный вами файл превышает 10 мегабайт.</b>');
			}
			else {	
				var decoded = $('<textarea/>').html(response['data']).val();
				$('#error').html('<b><span style="color:green !important">Файл успешно загружен.</span></b>');
				$('#transparent td').html(decoded);
			}
		}
	});
	
}); 

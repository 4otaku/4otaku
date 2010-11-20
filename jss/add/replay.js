$(document).ready(function(){  	

	window.processing_art = 0;
	
	replay = new AjaxUpload('replay_upload', {
		action: '/ajax.php?upload=postfile',
		name: 'filedata',
		multiple: true,
		data: {	  },
		autoSubmit: true,
		responseType: 'text/html',
		onSubmit: function(file, extension) {
			$(".processing").show();
			$('#error').html('');
			window.processing_replay = 1;
		},
		onComplete: function(file, response) {
			window.processing_replay = 0;
			$(".processing").hide(); 
			if (response == 'error-maxsize') {
				$('#error').html('<b>Ошибка! Выбранный вами файл превышает 10 мегабайт.</b>');
				$('#success').hide();
			} else {
				$('#transparent td').html(response);
				$('#success').show();
			}
		}
	});
	
}); 

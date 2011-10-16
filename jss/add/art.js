function check_similar() {
	var artValues = [];
	$('.art_images input').each(function() {
		artValues.push($(this).val().split("#")[1]);
	});
	$("#is_dublicates").load(window.config.site_dir+"/ajax.php?m=art&f=is_dublicates&data="+artValues.join(","));
}

$("#transparent td img.cancel").die("click").live("click", function(){  
	$(this).parent().remove();
	check_similar();
});

$(document).ready(function(){
	
	get_tags('art');

	window.processing_art = 0;
	
	art_upload = new qq.FileUploader({
		element: document.getElementById('art-image'),
		action: window.config.site_dir+'/ajax.php?upload=art',
		multiple: true,
		autoSubmit: true,
		onSubmit: 
			function(id, file) {
				$(".processing").show();
				$('#error').html('');
				window.processing_art++;
			},
		onComplete: 
		function(id, file, response) {
			window.processing_art = window.processing_art - 1;
			if (window.processing_art == 0) 
				$(".processing").hide(); 
			if (response['error'] == 'filetype') {
				$('#error').html('<b>Ошибка! Выбранный вами файл не является картинкой.</b>');
			}
			else if (response['error'] == 'maxsize') {
				$('#error').html('<b>Ошибка! Выбранный вами файл превышает 10 мегабайт.</b>');
			}
			else if (response['error'] == 'already-have') {
				$('#error').html('<b>Ошибка! Выбранный вами файл уже есть в базе.</b>');
			}
			else {
				if ($("input."+response['md5']).length < 1) {
					if ($("#art-image").attr('rel') == 'single') 
						$('#transparent td').html('');
						
					$('#transparent td').append('<div style="background-image: url('
						+response['image']+');"><img class="cancel" src="'
						+window.config.image_dir+'/cancel.png"><input type="hidden" name="images[]" value="'
						+response['data']+'" class="'+response['md5']+'"></div>');

					check_similar();
				}
			} 
		}
	});
	
	$(".art_upload_stop").click(
		function(){
			art_upload.cancel();
			window.processing_art = 0;
			$(".processing").hide(); 
			$('#error').html('');
		});
	
}); 

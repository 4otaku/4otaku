$(".fighting_character_needed").live("change", function(){
	var field_needed = false;

	$(".fighting_character_needed").each(function(){
		if ($(this).is(":checkbox") && $(this).is(":checked")) {
			field_needed = true;
		}

		if ($(this).is("select") && $(this).val() > 0) {
			field_needed = true;
		}
	})

	if (field_needed) {
		$(".fighting_character_list").slideDown();
	} else {
		$(".fighting_character_list").slideUp();
	}
});

if ($('#challenge-image').length > 0) image_upload = new qq.FileUploader({
	element: $('#challenge-image')[0],
	action: '/upload/',
	autoSubmit: true,
	onSubmit: function(id, file) {
		$(".processing-image").show();
		$('#error').html('');
		window.processing_image++;
	},
	onComplete: function(id, file, response) {
		window.processing_image = window.processing_image - 1;
		if (window.processing_image == 0) $(".processing-image").hide(); 
		if (response['error'] == 'filetype') {$('#error').html('<b>Ошибка! Выбранный вами файл не является картинкой.</b>');}
		else if (response['error'] == 'maxsize') {$('#error').html('<b>Ошибка! Выбранный вами файл превышает 2 мегабайт.</b>');}
		else {
			$('#transparent td').append('<div style="background-image: url('+response['image']+');"><img class="cancel" src="/new/templates/cancel.png"><input type="hidden" name="images[]" value="'+response['data']+'"></div>');
			$("#transparent td img.cancel").click(function(){  
				$(this).parent().remove();
			});
		}
	}
});

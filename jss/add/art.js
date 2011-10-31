window.enable_duplicates_check = true;

// TODO: вот где собака порылась. Заменить на установку индивидуальной галочки "главная пикча"
function check_similar() {
	if (window.enable_duplicates_check) {
		var artValues = [];
		$('.art_images div').each(function() {
			artValues.push($(this).attr('rel'));
		});
		$("#is_dublicates").load(window.config.site_dir+
			"/ajax.php?m=art&f=is_dublicates&data="+artValues.join(","));
	}
}

function uid() {
	var S4 = function() {
		return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
	};
	return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
}

function add_hidden_input(name, val, owner) {
	$('<input/>').attr('type', 'hidden').
		attr('name', 'images'+name).val(val).appendTo(owner);
}

$("#transparent td img.cancel").die("click").live("click", function(){  
	$(this).parent().remove();
	check_similar();
});
	
$(document).ready(function(){
	
	get_tags('art');

	window.processing_art = 0;
	
	var art_upload = new qq.FileUploader({
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
				if ($(".art_images[rel="+response.md5+"]").length < 1) {
					if ($("#art-image").attr('rel') == 'single') {
						$('#transparent td').html('');
					}
					
					var art = $('<div/>');
					art.css('background-image', 'url('+response.image+')');
					art.attr('rel', response.md5);
					art.append('<img class="cancel" src="'+window.config.image_dir+'/cancel.png">');
					
					var id = uid();
					
					add_hidden_input('['+id+'][animated]', response.animated, art);
					add_hidden_input('['+id+'][extension]', response.extension, art);
					add_hidden_input('['+id+'][md5]', response.md5, art);
					add_hidden_input('['+id+'][resized]', response.resized, art);
					add_hidden_input('['+id+'][thumb]', response.thumb, art);
					
					if (response.meta.id_group != undefined) {
						window.enable_duplicates_check = false;
						add_hidden_input('['+id+'][id_group]', response.meta.id_group, art);
					}
					if (response.meta.id_in_group != undefined) {
						add_hidden_input('['+id+'][id_in_group]', response.meta.id_in_group, art);
					}
					if (response.meta.tags != undefined) {
						$.each(response.meta.tags, function(key, tag) {
							add_hidden_input('['+id+'][tags][]', tag, art);
						});
					}
					
					art.appendTo('.art_images td');

					check_similar();
				}
			} 
		}
	});
	
}); 

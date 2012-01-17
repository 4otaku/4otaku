window.art_input_base_name = 'images';

function add_hidden_input(name, val, owner) {
	$('<input/>').attr('type', 'hidden').
		attr('name', window.art_input_base_name + name).val(val).appendTo(owner);
}

$("#transparent td img.cancel").die("click").live("click", function() {
	$(this).parent().remove();

	if ($('.image_holder').length > 1) {
		$(".as_variations").show();
	} else {
		not_variations();
		$(".as_variations").hide();
	}
});

$(".as_variations input").die("click").live("click", function(e) {
	if ($(this).val() != "Объединить") {
		not_variations();
	} else {
		choose_variations();
	}
});

function not_variations() {
	remove_mask();

	$(".as_variations input").val("Объединить");
	$(".master_image").remove();
}

function remove_mask() {
	$("body").css('cursor', 'auto');
	$("body").unbind('click');
	$(".choose_mask").remove();
	$(".as_variations, .image_holder").css('z-index', 'auto');
}

function choose_variations() {
	$(".as_variations input").val("Не объединять");
	$(".master_image").remove();

	$("body").css('cursor', 'url(/images/crown.png),crosshair');

	$(".as_variations, .image_holder").css('z-index', 6000);
	$("body").append('<div class="choose_mask" style="height:'+$(document).height()+'px;"></div>');

	$("body").bind('click', select_main_art);
}

function select_main_art(e) {

	var target = $(e.target);

	if (target.is(".as_variations input")) {
		return;
	}

	if (target.is('.image_holder')) {

		remove_mask();

		target.append('<img src="/images/crown.png" class="master_image" />');
		target.append('<input type="hidden" class="master_image"'+
			' name="images['+target[0].uid+'][master]" value="1" />');

	} else {
		not_variations();
	}
}

$(document).ready(function(){

	get_tags('art');

	window.processing_art = 0;

	var art_upload = new qq.FileUploader({
		element: document.getElementById('art-image'),
		action: window.config.site_dir+'/ajax.php?m=upload&f=art',
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
			if (window.processing_art == 0) {
				$(".processing").hide();
			}

			if (!response.success) {
				var error = response.data.error;

				if (error == 'filetype') {
					$('#error').html('<b>Ошибка! Выбранный вами файл не является картинкой.</b>');
				} else if (error == 'maxsize') {
					$('#error').html('<b>Ошибка! Выбранный вами файл превышает 10 мегабайт.</b>');
				} else if (error == 'exists') {
					$('#error').html('<b>Ошибка! Выбранный вами файл уже есть в базе.</b>');
				} else {
					$('#error').html('<b>Неизвестная ошибка.</b>');
				}
			} else {
				response = response.data;

				if ($(".image_holder[rel="+response.md5+"]").length < 1) {
					if ($("#art-image").attr('rel') == 'single') {
						$('#transparent td').html('');
					}

					var art = $('<div/>');
					art.addClass('image_holder');
					art.css('background-image', 'url('+response.image+')');
					art.attr('rel', response.md5);
					art.append('<img class="cancel" src="'+window.config.image_dir+'/cancel.png">');

					var id = uid();
					art[0].uid = id;

					add_hidden_input('['+id+'][animated]', response.animated, art);
					add_hidden_input('['+id+'][extension]', response.extension, art);
					add_hidden_input('['+id+'][md5]', response.md5, art);
					add_hidden_input('['+id+'][resized]', response.resized, art);
					add_hidden_input('['+id+'][thumb]', response.thumb, art);

					if (response.meta.id_group != undefined) {
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

					if ($('.image_holder').length > 1) {
						$(".as_variations").show();
					} else {
						not_variations();
						$(".as_variations").hide();
					}
				}
			}
		}
	});

});

$(".thickbox").unbind('click');
window.onbeforeunload = null;

$(document).ready(function(){

	window.processing_board = 0;
	$("select .selected").attr('selected', 'selected');

	$(".remove_link").live('click', function(e){
		if ($('.link_'+$(this).attr('rel')+' tr.link').length > 1 || $(this).attr('rel') == 'file')
			$(this).parents('tr.link').remove();
	});

	$(".add_link").click(function(){
		var parent = $('.link_'+$(this).attr('rel'));

		var new_link = parent.children("tr.link:first").clone();
		new_link.find(".input_link").val("");
		new_link.appendTo(parent);
	});

	board_upload = new qq.FileUploader({
		element: document.getElementById('board-image'),
		action: window.config.site_dir+'/ajax.php?m=upload&f=board',
		autoSubmit: true,
		onSubmit: function(id, file) {
			$(".processing").show();
			$('#error').html('');
			window.processing_board = 1;
		},
		onComplete: function(id, file, response) {
			window.processing_board = 0;
			$(".processing").hide();

			if (!response.success) {
				var error = response.data.error;

				if (error == 'filetype') {
					$('#error').html('<b>Ошибка! Выбранный вами файл не является картинкой.</b>');
				} else if (error == 'maxsize') {
					$('#error').html('<b>Ошибка! Выбранный вами файл превышает 10 мегабайт.</b>');
				} else {
					$('#error').html('<b>Неизвестная ошибка.</b>');
				}
			} else {
				response = response.data;

				$('#transparent td').append('<div style="background-image: url('
					+response['image']+');"><img class="cancel" src="'
					+window.config.image_dir+'/cancel.png"><input type="hidden" name="image[]" value="'
					+response['data']+'"></div>');
				$("#transparent td img.cancel").click(function(){
					$(this).parent().remove();
				});
			}
		}
	});

	if (document.location.hash.indexOf("#reply-") == 0) {
		var id = parseInt(document.location.hash.replace("#reply-",""));
		$("#textfield").html(">>"+id+"\n");
	}

	$("#addform").attr("action", document.location.href.split('#')[0]);

	$(".add_random").click(function(){
		$('#transparent td').append('<div style="background-image: url(/images/dice.jpg); background-repeat: repeat">'+
			'<img class="cancel" src="/images/cancel.png">'+
			'<span class="random_select_container center">'+
			'<select name="image[]" class="random_select">'+
				'<option value="random_main">С главной</option>'+
				'<option value="random_flea">Из барахолки</option>'+
				'<option value="random_cg">Из CG-паков</option>'+
				'<option value="random_sprite">Из спрайтов</option>'+
			'</div></select></div>');
		$("#transparent td img.cancel").click(function(){
			$(this).parent().remove();
		});		
	});
});

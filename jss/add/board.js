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
		action: window.config.site_dir+'/ajax.php?upload=board',
		autoSubmit: true,
		onSubmit: function(id, file) {
			$(".processing").show();
			$('#error').html('');
			window.processing_board = 1;
		},
		onComplete: function(id, file, response) {
			window.processing_board = 0;
			$(".processing").hide(); 
			if (response['error'] == 'filetype') {
				$('#error').html('<b>Ошибка! Выбранный вами файл не является картинкой.</b>');
			}
			else if (response['error'] == 'maxsize') {
				$('#error').html('<b>Ошибка! Выбранный вами файл превышает 5 мегабайт.</b>');
			}
			else if (response['error'] == 'flashmaxsize') {
				$('#error').html('<b>Ошибка! Выбранный вами файл превышает 10 мегабайт.</b>');
			}
			else {
				$('#transparent td').append('<div style="background-image: url('
					+response['image']+');"><img class="cancel" src="'
					+window.config.image_dir+'/cancel.png"><input type="hidden" name="image[]" value="'
					+response['data']+'"></div>');
				$("#transparent td img.cancel").click( function(){  
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
	
	if (document.location.hash.indexOf("#reply-") == 0) {
		var id = parseInt(document.location.hash.replace("#reply-",""));
		$("#textfield").html(">>"+id+"\n");
	}

	$("#addform").attr("action",document.location.href.split('#')[0]);
	
});

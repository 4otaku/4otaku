String.prototype.replaceall = function(oldstr, newstr) {
	var target = this;
	var check = target.indexOf(oldstr);
	while (check != -1){
		target = target.replace(oldstr,newstr);
		check = target.indexOf(oldstr);
	}
	return(target);
}

$(document).ready(function(){

	get_tags('post');

	window.processing_art = 0;
	window.processing_files = 0;
	window.processing_torrents = 0;

	$(".remove_link").live('click', function(e){
		if ($('.link_'+$(this).attr('rel')+' tr.link').length > 1 ||
			$(this).attr('rel') == 'file' || $(this).attr('rel') == 'torrent') {
			$(this).parents('tr.link').remove();
		}
	});

	$(".add_link").click(function(){
		var oldnum = $('.link_'+$(this).attr('rel')).find("tr.link:last").attr('rel');
		var num = uid();

		var string = '<tr class="link">'+$('.link_'+$(this).attr('rel')).find("tr.link:last").html().replaceall('['+oldnum+']','['+num+']')+'</tr>';

		if ($('.link_'+$(this).attr('rel')).is('.link-edit')) {
			string = '<table>' + string + '</table>';
		}

		$('.link_'+$(this).attr('rel')).append(string);
		$('.link_'+$(this).attr('rel')).find("tr.link:last").attr('rel', num);
		if ($(this).attr('rel') == 'main') {
			$('.link_main tr.link:last select').val($('.link_main tr.link:last').prev().find('select').val());
		}
		if ($('.link_'+$(this).attr('rel')).find("tr.link:last img").length) {
			$('.link_'+$(this).attr('rel')).find("tr.link:last input[type!='submit']").each(function(){
				$(this).val("");
			});
		}
		if ($('.link_'+$(this).attr('rel')).find("tr.link:last :checkbox").length) {
			$('.link_'+$(this).attr('rel')).find("tr.link:last :checkbox").each(function(){
				$(this).attr('checked', true);
			});
		}
		$(".arrow-down:hidden").show();
		$(".arrow-down:visible:last").hide();
	});

	if ($('#post-image').length > 0) image_upload = new qq.FileUploader({
		element: document.getElementById('post-image'),
		action: window.config.site_dir+'/ajax.php?upload=postimage',
		autoSubmit: true,
		onSubmit: function(id, file) {
			$(".processing-image").show();
			$('#error').html('');
			window.processing_art++;
		},
		onComplete: function(id, file, response) {
			window.processing_art = window.processing_art - 1;
			if (window.processing_art == 0) $(".processing-image").hide();
			if (response['error'] == 'filetype') {$('#error').html('<b>Ошибка! Выбранный вами файл не является картинкой.</b>');}
			else if (response['error'] == 'maxsize') {$('#error').html('<b>Ошибка! Выбранный вами файл превышает 2 мегабайт.</b>');}
			else {
				$('#transparent td').append('<div style="background-image: url('+response['image']+');" class="left right20"><img class="cancel" src="'+window.config.image_dir+'/cancel.png"><input type="hidden" name="image[]" value="'+response['data']+'"></div>');
				$("#transparent td img.cancel").click(function(){
					$(this).parent().remove();
				});
			}
		}
	});

	$("#transparent td img.cancel").click(function(){
		$(this).parent().remove();
	});

	if ($('#post-file').length > 0) file_upload = new qq.FileUploader({
		element: document.getElementById('post-file'),
		action: window.config.site_dir+'/ajax.php?upload=postfile',
		autoSubmit: true,
		onSubmit: function(id, file) {
			$(".processing-file").show();
			$('#error').html('');
			window.processing_files++;
		},
		onComplete: function(id, file, response) {
			window.processing_files = window.processing_files - 1;
			if (window.processing_files == 0) $(".processing-file").hide();
			if (response['error'] == 'maxsize') {$('#error').html('<b>Ошибка! Выбранный вами файл превышает 10 мегабайт.</b>');}
			else {
				var decoded = $('<textarea/>').html(response['data']).val();

				if ($('.link_file').children("tr.link:last").length != 0)
					var num = parseInt($('.link_file').children("tr.link:last").attr('rel')) + 1;
				else num = 1;
				if ($('#post-file').attr('rel') == 'add') $('.link_file').append('<tr class="link" rel="0"><td class="input field_name">Прикрепленный файл</td><td class="inputdata">'+decoded.replaceall('[0]','['+num+']')+'</td></tr>');
				else $('.link_file').append('<tr class="link" rel="0"><td>'+decoded.replaceall('[0]','['+num+']')+'</td><td class="handler"><img src="/images/str.png" /></td></tr>');
				$('.link_file').children("tr.link:last").attr('rel', num);
			}
		}
	});

	if ($('#post-torrent').length > 0) file_upload = new qq.FileUploader({
		element: document.getElementById('post-torrent'),
		action: window.config.site_dir+'/ajax.php?upload=posttorrent',
		autoSubmit: true,
		onSubmit: function(id, file) {
			$(".processing-torrent").show();
			$('#error').html('');
			window.processing_torrents++;
		},
		onComplete: function(id, file, response) {
			window.processing_torrents = window.processing_torrents - 1;
			if (window.processing_torrents == 0) {
				$(".processing-torrent").hide();
			}
			if (response['error'] == 'incorrect') {
				$('#error').html('<b>Ошибка! Выбранный вами файл не является торрентом.</b>');
			} else if (response['error'] == 'maxsize') {
				$('#error').html('<b>Ошибка! Выбранный вами файл превышает 10 мегабайт. Вам надо загрузить торрент-файл, а не содержимое торрента.</b>');
			} else if (response['error'] == 'exists') {
				$('#error').html('<b>Ошибка! Выбранный вами торрент уже добавлен и прикреплен к записи №'+response['post_id']+'.</b>');
			} else {
				var decoded = $('<textarea/>').html(response['data']).val();

				if ($('.link_torrent').children("tr.link:last").length != 0) {
					var num = parseInt($('.link_torrent').children("tr.link:last").attr('rel')) + 1;
				} else {
					num = 1;
				}

				if ($('#post-torrent').attr('rel') == 'add') {
					$('.link_torrent').append('<tr class="link" rel="0"><td class="input field_name">Торрент</td><td class="inputdata">'+decoded.replaceall('[0]','['+num+']')+'</td></tr>');
				} else {
					$('.link_torrent').append('<tr class="link" rel="0"><td>'+decoded.replaceall('[0]','['+num+']')+'</td><td class="handler"><img src="/images/str.png" /></td></tr>');
				}

				$('.link_torrent').children("tr.link:last").attr('rel', num);
			}
		}
	});

});

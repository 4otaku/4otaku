$(document).ready(function(){
	new qq.FileUploader({
		element: document.getElementById('upload'),
		action: window.config.site_dir+'/ajax.php?m=upload&f=pack&name='+$('.upload_name').val()+'&text='+$('.upload_text').val(),
		multiple: false,
		autoSubmit: true,
		onSubmit: function(id, file) {
			$(".processing").show();
			$('#error').html('');
		},
		onComplete: function(id, file, response) {
			$(".processing").hide();

			if (!response.success) {
				var error = response.data.error;

				if (error == 'maxsize') {
					$('#error').html('<b>Ошибка! Выбранный вами файл превышает 200 мегабайт.</b>');
				} else if (error == 'exists') {
					$('#error').html('<b>Ошибка! Архив с таким названием уже добавлен.</b>');
				} else {
					$('#error').html('<b>Неизвестная ошибка.</b>');
				}

			} else {
				response = response.data;

				update(response.id);
				create_progressform(response.id,response.file);
			}
		}
	});
});

function update(id) {
	$.post("/engine/process_pack.php");
	if ($(".progress-"+id).length > 0) {
		$("#progress-"+id+" .updating").show();$(".progress-"+id).hide();
		$(".progress-"+id).load("/engine/status_pack.php?id="+id, function (){
			$("#progress-"+id+" .updating").hide();$(".progress-"+id).show();
			if ($(".progress-"+id).html() != "Готово.") setTimeout("update('"+id+"');",15000);
		});
	} else {
		setTimeout("update('"+id+"');",15000);
	}
}

function create_progressform(id,name,input) {
	$("#mainpart").append("<table><tr><td colspan='3' id='progress-"+id+"'>Прогресс файла "+name+" - <div class='progress-"+id+"'>ожидание</div><img class='updating hidden' src='/images/ajax-processing.gif' /></td></tr></table>");
}

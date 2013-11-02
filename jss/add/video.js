$(document).ready(function(){

	get_tags('video');

	$('#addform').data('beforesubmit', function(){
		if (
			!$('[name="title"]').val().length
		) {
			alert('Для добавления видео надо указать заголовок.');
			return false;
		}

		if (
			!$('[name="link"]').val().length
		) {
			alert('Для добавления видео необходимо добавить ссылку.');
			return false;
		}

		return true;
	});
});

$.ajaxSetup({
	url: "/challenge/",
	global: false,
	type: "POST",
	cache: false,
	data: {
		ajax: true,
		cookie: $.cookie('beta_settings')
	}
});

function register_unload () {
	window.onbeforeunload = function (e) {

		var message = 'Вы точно хотите уйти с этой страницы?'+
			' Если вы уйдете, то данные в форме добавления будут потеряны.';
		if (typeof e == 'undefined') e = window.event;
		if (e) e.returnValue = message;
		return message;
	}	
}

function unregister_unload () {
	window.onbeforeunload = null;
}

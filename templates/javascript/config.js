$.ajaxSetup({
	url: "/",
	global: false,
	type: "POST",
	cache: false,
	data: {
		ajax: true,
		cookie: $.cookie('settings')
	}
});

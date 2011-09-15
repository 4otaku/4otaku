function reload_user_menu() {
	$(".header_private_item ul").load("/ajax.php?m=menu&f=get", function() {
		tb_remove();
		tb_init('a.thickbox');
	});
}

$(document).ready(function() {
	$("select .selected").attr('selected', 'selected');

	$(".submit_personal_menu_item").unbind('click').click(function(){
		var form = $(this).parents(".header_menu_input_table"),
			url = form.find("input[name=url]").val(),
			name = form.find("input[name=name]").val(),
			url_regex = new RegExp("^(https?|ftp):\/\/|^\/");

		url = url.replace(/^http:\/\/4otaku\.ru\/?/i, "/");

		if (name.length == 0 || !url_regex.test(url)) {
			return;
		}

		$.post("/ajax.php?m=menu&f=add&url="+
			urlencode(url)+"&name="+
			urlencode(name),
			reload_user_menu);
	});

	$(".edit_personal_menu_item").unbind('click').click(function(){
		var form = $(this).parents(".header_menu_input_table"),
			url = form.find("input[name=url]").val(),
			name = form.find("input[name=name]").val(),
			order = form.find("select[name=order]").val(),
			id = form.find("input[name=id]").val(),
			url_regex = new RegExp("^(https?|ftp):\/\/|^\/");

		url = url.replace(/^http:\/\/4otaku\.ru\/?/i, "/");

		if (name.length == 0 || !url_regex.test(url)) {
			return;
		}

		$.post("/ajax.php?m=menu&f=edit&url="+
			urlencode(url)+"&name="+
			urlencode(name)+"&order="+
			urlencode(order)+"&id="+
			urlencode(id),
			reload_user_menu);
	});

	$(".delete_personal_menu_item").unbind('click').click(function(){
		var form = $(this).parents(".header_menu_input_table"),
			name = form.find("input[name=name]").val(),
			id = form.find("input[name=id]").val();

		if (!confirm('Вы уверены, что хотите удалить "'+name+'" из меню?')) {
			return;
		}

		$.post("/ajax.php?m=menu&f=delete&id="+id, reload_user_menu);
	});
});

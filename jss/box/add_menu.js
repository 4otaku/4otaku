$(document).ready(function() {
	$(".submit_personal_menu_item").unbind('click').click(function(){
		var form = $(this).parents(".header_menu_input_table"),
			url = form.find("input[name=url]").val(),
			name = form.find("input[name=name]").val(),
			order = 0,
			url_regex = new RegExp("^(https?|ftp):\/\/|^\/");

		url = url.replace(/^http:\/\/4otaku\.ru\/?/i, "/");

		if (name.length == 0 || !url_regex.test(url)) {
			return;
		}

		$("<a/>").html(name).attr("url", url).
			appendTo("<li/>").parent().
			appendTo(".header_private_item ul");

		$(".header_private_item ul li.box").
			appendTo(".header_private_item ul");

		$.post("/ajax.php?m=menu&f=add&url="+
			urlencode(url)+"&name="+
			urlencode(name),
			tb_remove);
	});
});

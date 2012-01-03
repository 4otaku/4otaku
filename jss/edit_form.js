if (!window.halt_onbeforeunload) {
	window.onbeforeunload = function (e) {
		var message = 'Вы точно хотите уйти с этой страницы? Если вы уйдете, то данные в форме редактирования будут потеряны.';
		if (typeof e == 'undefined') e = window.event;
		if (e) e.returnValue = message;
		return message;
	}
}

function get_data() {
	return $("form#edit_post").serialize();
}

function get_data_new() {
	data = $("form#edit_form").serializeArray();

	$.each(data, function(key, item) {
		if (item.name == "type") {
			delete data[key];
		}

		item.name = item.name.replace(/^images\[.*?\]/, 'image');
	});

	return data;
}

function set_loading(id) {
	$("#post-"+id).css('height', $("#post-"+id).height());
	$("#post-"+id).html('<table width="100%" height="100%">'+
		'<tr><td align="center" valign="center">'+
		'<img src="'+window.config.image_dir+'/ajax-loader.gif">'+
	'</td></tr></table>');
}

function set_loading_new(id, type) {
	var item = $("#"+type+"-"+id);

	item.css('height', item.height());
	item.html('<table width="100%" height="100%">'+
		'<tr><td align="center" valign="center">'+
		'<img src="'+window.config.image_dir+'/ajax-loader.gif">'+
	'</td></tr></table>');
}

function on_load(id, type) {

	if (window.full_reload == true) {
		document.location.reload();
	} else {
		$("#post-"+id).load(window.config.site_dir+"/ajax.php?m=edit&f=show&id="+id+"&type="+type+"&num="+$("input[name='save']").attr('rel')+"&path="+location.pathname ,function(){
			$("div.display_item").css({'height':'auto'});
			$(".art_translation").easyTooltip();
		});
		$("div.edit_field").html(''); $("div.edit_field").hide();
		$('body').css('cursor','default');
	}
}

function on_load_new(id, type) {

	if (window.full_reload == true) {
		document.location.reload();
	} else {
		var url_location = document.location.href.split('/')[4];

		if (typeof url_location == 'undefined' || !url_location.match(/^\d+$/)) {
			var single = 'batch';
		} else {
			var single = 'single';
		}

		var item = $("#"+type+"-"+id);

		// @TODO: Legacy hack. Delete when unneeded.
		if (item.length == 0) {
			item = $("#post-"+id);
			item.load("/ajax.php?m=edit&f=show&id="+id+"&type="+type+"&num="+$("input[name='save']").attr('rel')+"&path="+location.pathname ,function(){
				$("div.display_item").css({'height':'auto'});
				$(".art_translation").easyTooltip();
			});
		} else {
			item.load("/"+type+"/show/"+id+"/"+single+"/" ,function(){
				$("div.display_item").css({'height':'auto'});
				$(".art_translation").easyTooltip();
				if ($("#navi_bottom").length) {
					var base = $("#navi_bottom").attr('rel');
					item.find('p.meta a').each(function(key, link){
						$(link).attr('href', base + $(link).attr('href'));
					});
				}
			});
		}
		$("div.edit_field").html('');
		$("div.edit_field").hide();
		$('body').css('cursor','default');
	}
}

function uid() {
	var S4 = function() {
		return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
	};
	return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
}

$(document).ready(function(){

	$("input.disabled").click(function(event){
		event.preventDefault();
	});

	$("input.save_changes").unbind('click').click(function(e){
		e.preventDefault();
		window.onbeforeunload = null;

		var post = get_data();
		var type = $("form#edit_post input[name='type']").val();
		var id = $("form#edit_post input[name='id']").val();

		set_loading(id);
		$.post(window.config.site_dir+"/ajax.php?m=edit&f=save",
			post, function(){ on_load(id, type);});

	});

	$("input.save_changes_new").unbind('click').click(function(e){
		e.preventDefault();
		window.onbeforeunload = null;

		var post = get_data_new();
		var type = $("form#edit_form input[name='type']").val();
		var id = $("form#edit_form input[name='id']").val();

		set_loading_new(id, type);
		$.post("/"+type, post, function(){ on_load_new(id, type);});

	});

	$("input.save_on_enter").keydown(function(e){
		switch (e.which) {
			case 13:
				e.preventDefault();
				window.onbeforeunload = null;
				$(this).parents('.edit_wrap').find('input.save_changes').click();
				break;
			default:
				break;
		}
	});

	$("input.drop_changes").click(function(){
		window.onbeforeunload = null;
		$("div.edit_field").html('');
		$("div.edit_field").hide();
		window.full_reload = false;
	})

	$(".add_meta").click(function(){
		$(this).parent().append($(this).parent().children("select:last").clone());
		$(this).parent().children(".remove_meta").show();
	});

	$(".remove_meta").click(function(){
		$(this).parent().children("select:last").remove();
		if ($(this).parent().children("select").length < 2) $(this).hide();
	});

	$("div.loader").hide();
	$("div.edit_wrap").parent().show();
});

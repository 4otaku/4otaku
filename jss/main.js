jQuery.cookie = function (key, value, options) {
	if (arguments.length > 1 && (value === null || typeof value !== "object")) {
		options = jQuery.extend({}, options);
		if (value === null) { options.expires = -1; }

		if (typeof options.expires === 'number') {
			var days = options.expires, t = options.expires = new Date();
			t.setDate(t.getDate() + days);
		}

		return (document.cookie = [
			encodeURIComponent(key), '=',
			options.raw ? String(value) : encodeURIComponent(String(value)),
			options.expires ? '; expires=' + options.expires.toUTCString() : '',
			options.path ? '; path=' + options.path : '',
			options.domain ? '; domain=' + options.domain : '',
			options.secure ? '; secure' : ''
		].join(''));
	}

	options = value || {};
	var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
	return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};

	/* Some Cookie Magic

	if (typeof localStorage == 'object') {
		try {
			var cookie = $.cookie("settings");
			var local = localStorage.getItem('4otaku_settings');

			if (cookie != null && local == null) {
				localStorage.setItem('4otaku_settings', cookie);
			} else if (cookie != local) {
				$.cookie("settings", local, { expires: 60 , path : window.config.site_dir + '/' , domain : '4otaku.ru'});
			}
		} catch (err) {}
	} */

(function($) {
	$.fn.easyTooltip = function(options){
		var defaults = {
			xOffset: 10,
			yOffset: 25,
			tooltipId: "easyTooltip",
			clickRemove: true,
			content: "",
			useElement: "",
			timeOut: false
		};
		var options = $.extend(defaults, options);
		var content;
		this.each(function() {
			var title = $(this).attr("title");
			$(this).hover(function(e){
				$("#" + options.tooltipId).remove();
				$(this).attr("title","");
				content = (options.content != "") ? options.content : title;
				content = (options.useElement != "") ? $("#" + options.useElement).html() : content;
				if (content != "" && content != undefined){
					$("body").append("<div id='"+ options.tooltipId +"'>"+ content +"</div>");
					if (options.timeOut) {
						$("#" + options.tooltipId)
							.css("position","absolute")
							.css("top",(e.pageY - options.yOffset) + "px")
							.css("left",(e.pageX + options.xOffset) + "px")
							.css("display","none");
							setTimeout(function() {$("#" + options.tooltipId).fadeIn("fast")}, options.timeOut);
					} else {
						$("#" + options.tooltipId)
							.css("position","absolute")
							.css("top",(e.pageY - options.yOffset) + "px")
							.css("left",(e.pageX + options.xOffset) + "px")
							.css("display","none").fadeIn("fast");
					}
				}
			},
			function(){
				$("#" + options.tooltipId).remove();
				$(this).attr("title",title);
			});
			$(this).mousemove(function(e){
				$("#" + options.tooltipId)
					.css("top",(e.pageY - options.yOffset) + "px")
					.css("left",(e.pageX + options.xOffset) + "px")
			});
			if(options.clickRemove){
				$(this).mousedown(function(e){
					$("#" + options.tooltipId).remove();
					$(this).attr("title",title);
				});
			}
		});
	};
})(jQuery);

jQuery.download = function(url, data, method){
	if(url && data){
		data = typeof data == 'string' ? data : jQuery.param(data);
		var inputs = '';
		jQuery.each(data.split('&'), function(){
			var pair = this.split('=');
			inputs+='<input type="hidden" name="'+ pair[0] +'" value="'+ pair[1] +'" />';
		});
		jQuery('<form action="'+ url +'" method="'+ (method||'get') +'">'+inputs+'</form>')
		.appendTo('body').submit().remove();
	};
};


function trim (str) {
	str = str.replace(/^\s+/, '');
	for (var i = str.length - 1; i >= 0; i--) {
		if (/\S/.test(str.charAt(i))) {
			str = str.substring(0, i + 1);
			break;
		}
	}
	return str;
}

function urlencode(str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}

function show_updates(id) {
	$("div#updates_field_loader img").show();
	$("div#updates_field").load(window.config.site_dir+"/ajax.php?m=post&f=show_updates&id="+id, function(){
		$("div#updates_field_loader img").hide();
		$("div#updates_field").slideDown();
	});
}

function add_text(text) {
	if ($("#textfield").length == 1) {
		$("#textfield").append(text);
		scrollTo(0,0);
	} else {
		var id = parseInt(text.replace(">>",""));
		document.location.hash = "#reply-"+id;
		$("div#downscroller a.disabled").click();
		scrollTo(0,0);
	}
}

function is_left_click(event) {
	if(event.button == undefined) {
		// Клик вызван функцией jQuery .click(), считаем за левый
		return true;
	}

	if((!$.browser.msie && event.button == 0) || ($.browser.msie && event.button == 1)) {
		return true;
	}
	return false;
}

$(document).ready(function(){

	/* Shared settings */

	$(".checked").attr('checked', true);
	$(".not_checked").attr('checked', false);

	if ($("div#comments-field").length == 1) {
		$("div#comments-field").load(window.config.site_dir+"/ajax.php?m=add&f=comment", function(){
			if (document.location.hash.indexOf("#reply-") == 0) {
				var id = parseInt(document.location.hash.replace("#reply-",""));
				if ($('#reply-'+id).length == 1) {
					$('#reply-'+id).append($('#comments-field'));
					$("#comment-parent").val(id);
					$("#comment-main").show();
					$(".commentsh2").hide();
				}
			}

			$(".email_subscription").click(function(){
				$(".email_subscription_field").slideDown();
			});
		});
	}

	if ($(".bug-add").length == 1) {
		$(".bug-add").load(window.config.site_dir+"/ajax.php?m=bugs&f=add", function(){
			$(".bug-add").css("text-align", "left");
		});
	}

	if ($(".bug-comment-add").length == 1) {
		$(".bug-comment-add").load(window.config.site_dir+"/ajax.php?m=bugs&f=comment", function(){
			$(".bug-comment-add").css("text-align", "left");
		});
	}

	$("#navi_bar select").find('option:first').attr('selected', 'selected');
	$("select .selected").attr('selected', 'selected');

	$("#navi_bar select").live('change',function(){
		var i = 0; var line = 'mixed/'; window.navigation = new Array();
		$("#navi_bar select").each(function(){
			if ($(this).val() != 'empty')
				window.navigation[$(this).attr('rel')] = window.navigation[$(this).attr('rel')] + ',' + $(this).val();
		});
		for (var key in window.navigation) {
			window.navigation[key] = window.navigation[key].replace('undefined,','');
			i++;
		}
		if (i == 0) {
			$(".navigation_link").hide();
		}
		else {
			if (i == 1 && window.navigation[key].indexOf(',') == -1) {
				$(".navigation_link a").attr('href',$(".navigation_link a").attr('rel')+key+'/'+window.navigation[key]+'/').parent().show();
			}
			else {
				for (var key in window.navigation) line = line + key + '=' + window.navigation[key] + '&';
				$(".navigation_link a").attr('href',$(".navigation_link a").attr('rel')+line).parent().show();
			}
		}
	});

	$(".add_navi").click(function(){
		$(this).parents("div.selector").append("<span>"+$(this).parents("div.selector").children("span:last").html().replace('add_navi"','remove_navi"').replace("+","-")+"</span>");
	});

	$(".remove_navi").live('click',function(){
		$(this).parent().remove();
	});

	$(".with_help").easyTooltip();
	$("a.with_help2").easyTooltip({	xOffset: -50, yOffset: -25 });

	$(".disabled").live('click',function(event){
		event.preventDefault();
	});

	$(".box a").each(function(){
		var vars = $(this).attr('href').split('=');
		var height = parseInt(vars[vars.length-1]);
		if (height + 100 > $(window).height())
			$(this).attr('href',$(this).attr('href').replace('height='+height,'height='+($(window).height()-100)));
	});

	$("a.reply").click(function(event){
		event.preventDefault();
		$('#reply-'+$(this).attr('rel')).append($('#comments-field'));
		$("#comment-parent").val($(this).attr('rel'));
		$("#comment-main").show();
		$(".commentsh2").hide();
	});

	/* Saving preferences */

	$(".settings").change(function(){
		if ($(this).is(':checkbox')) val = '{'+$(this).is(':checked')+'}';
		else val = $(this).val();
		$.post(window.config.site_dir+"/ajax.php?m=cookie&f=set&field="+$(this).attr('rel')+"&val="+val, function() {
			document.location.reload();
		});
	});

	$(".show_nsfw").click(function(){
		$(this).parents(".post table").find("tr.hidden").show();
		$(this).parents(".post table tr").hide();
	});

	$(".always_show_nsfw").click(function(){
		$.post(window.config.site_dir+"/ajax.php?m=cookie&f=set&field=show.nsfw&val=1", function() {
			document.location.reload();
		});
	});

	$(".bar_arrow").click(function(event){
		event.preventDefault();
		if ($(this).parent().find('img').attr('src') == window.config.image_dir+"/text2387.png") {
			$(this).parent().find('img').attr('src',window.config.image_dir+"/text2391.png");
			$('#'+$(this).attr('rel')+'_bar').slideDown();
			$.post(window.config.site_dir+"/ajax.php?m=cookie&f=set&field=dir."+$(this).attr('rel')+"&val=1");
		}
		else {
			$(this).parent().find('img').attr('src',window.config.image_dir+"/text2387.png");
			$('#'+$(this).attr('rel')+'_bar').slideUp();
			$.post(window.config.site_dir+"/ajax.php?m=cookie&f=set&field=dir."+$(this).attr('rel')+"&val=0");
		}
	});

	$(".logout").live("click", function(){
		$.cookie("settings", "", -1);
		document.location.reload();
	});

	/* For calling out adding and editing fields */

	$("div#downscroller a.disabled").click(function(){
		if (!($(this).attr('rel')=='on')) {
			if ($("div#downscroller input.password").length == 1) {
				$.post(window.config.site_dir+"/ajax.php?m=add&f=checkpassword&val="+$("div#downscroller input.password").val()+"&id="+$("div#downscroller input.password").attr('rel'), function(data) {
					if (data == 'success') {
						$("div#add_loader img").show();
						$("div.edit_field").html(''); $("div.edit_field").hide();
						vars = $("div#downscroller a.disabled").parent().parent().attr('rel').split('#');
						$("div#add_form").load("/ajax.php?m=add&f="+vars[0]+'&info='+vars[1]);
					}
					else $(".closed_group").slideDown();
				});
			} else {
				$("div#add_loader img").show();
				$("div.edit_field").html(''); $("div.edit_field").hide();
				if ($(this).attr("href") == "#scroll-draw") {
					$("div#add_form").html("<iframe src='http://draw.4otaku.ru/form/' width='100%' height='300'>Ваш браузер не поддерживает Iframe</iframe>").show();
					$("div#add_loader img").hide();
				} else {
					if ($("#add_form").attr('rel')) var id = "&id=" + $("#add_form").attr('rel'); else var id = "";
					vars = $(this).parent().parent().attr('rel').split('#');
					$("div#add_form").load(window.config.site_dir+"/ajax.php?m=add&f="+vars[0]+'&info='+vars[1]+id);
				}
			}
		} else {
			if ($("#addform input.password").length == 1) $("#addform input.password").parent().prependTo("div#downscroller");
			$("div#downscroller a.disabled").attr('rel','off');
			$("#add_form").hide();
			window.onbeforeunload = null;
			$("#add_form").html('');
			$("div#downscroller span.arrow").html(' ↓');
		}
	});

	if (
		$("#downscroller").length == 1 &&
		$("#downscroller").attr('rel').indexOf('board#') == 0
	) {
		if (document.location.hash.indexOf("#reply") == 0) {
			$("div#downscroller a.disabled").click();
		}
	}

	$("input.edit").click(function(event){
		$("div.edit_field").html(''); $("div.edit_field").hide();
		$("div#downscroller a.disabled").attr('rel','off');
		$('.booru_main img').unbind('mousemove');
		$("#add_form").hide();
		window.onbeforeunload = null;
		$("#add_form").html('');
		$("div#downscroller span.arrow").html(' ↓');
		var rel = $(this).attr("rel");
		var numeric = window.location.pathname.split('/')[2];
		$("div#loader-"+rel).show();
		$("div#edit-"+rel).load(window.config.site_dir+"/ajax.php?m=edit&f="+$("#edit_type-"+rel+" option:selected").val()+"&id="+rel+"&type="+$("div#edit-"+rel).attr("rel")+"&num="+numeric);
	});

	$("input.full_reload").click(function(event){
		window.full_reload = true;
	});

	$("a.car-toggler").click(function(event){
		event.preventDefault();
		if ( $(this).attr('rel') == 'closed' ) {
			$(this).attr('rel','open');
			$(this).html('Свернуть все');
			$(".car-monthlisting").slideDown();
		}
		else {
			$(this).attr('rel','closed');
			$(this).html('Развернуть все');
			$(".car-monthlisting").slideUp();
		}
	});

	$(".edit_comment").click(function(){
		$('.edit-'+$(this).attr('rel')).load("/ajax.php?m=edit&f=comment&id="+$(this).attr("rel"), function(){ });
	});

	/* Scrolling in and out lists */

	$(".car-yearmonth").click(function(event){
		if ( $(this).attr('rel') == 'closed' ) {
			$(this).attr('rel','open');
			id = $(this).attr('class').split(' ');
			if (id[2] == 'remember') $.post(window.config.site_dir+"/ajax.php?m=cookie&f=set&field=order."+id[3]+"&val=1");
			$("ul."+id[1]).slideDown();
		}
		else {
			$(this).attr('rel','closed');
			id = $(this).attr('class').split(' ');
			if (id[2] == 'remember') $.post(window.config.site_dir+"/ajax.php?m=cookie&f=set&field=order."+id[3]+"&val=0");
			$("ul."+id[1]).slideUp();
		}
	});

	/* Main page section start */

	$("a.compress_news").click(function(event){
		event.preventDefault();
		$(".artholder").append($(".artblock")).show();
		$(".videoholder").append($(".videoblock")).show();
		$(".index_largecolumn").hide();
		$(".compressed_news").show();
		$.post(window.config.site_dir+"/ajax.php?m=cookie&f=set&field=news.read&val={time}");
	});

	$("a.uncompress_news").click(function(event){
		event.preventDefault();
		$(".defaultartholder").append($(".artblock"));
		$(".artholder").hide();
		$(".defaultvideoholder").append($(".videoblock")).show();
		$(".videoholder").hide();
		$(".index_largecolumn").show();
		$(".compressed_news").hide();
		$.post(window.config.site_dir+"/ajax.php?m=cookie&f=set&field=news.read&val=0");
	});

	/* Main page section end */

	/* Post section start */

	if (window.location.pathname.search('/show_updates/') != -1) show_updates($(".show_updates").attr('rel'));

	$("div div.handler a").live('click', function(event){
		if ($(this).parent().children("span").html() == "↓") {
			$(this).parent().children("span").html("↑");
			$(this).parent().parent().children("div.text").slideDown();
		}
		else {
			$(this).parent().children("span").html("↓");
			if ($(this).parent().parent().find("img").length > 0)
				$(this).parent().parent().children("div.text").hide();
			else
				$(this).parent().parent().children("div.text").slideUp();
		}
	});

	$("a.imageholder, a.similar_navi").hover(function(){
		var imagethumb = $(this).find(".hiddenthumb");
		if (imagethumb.attr('src') == '#') {
			imagethumb.attr('src',imagethumb.attr('rel'));
		}
		imagethumb.css( {left : ($(this).offset().left + 15) + 'px',top : parseInt($(this).offset().top) - 15 - imagethumb.parent().attr('rel') + 'px'} ).show();
	}, function(){
		$(this).find(".hiddenthumb").hide();
	});

	$(".show_updates").live('click',function(){
		show_updates($(this).attr("rel"));
	});

	$(".synonims a.disabled").live('click',function(event){
		if (is_left_click(event)) {
			if ($(this).html()=='&gt;&gt;') {
				$('.tag_synonims_'+$(this).attr('rel')).show();
				$(this).attr('title','Спрятать синонимы');
				$(this).html('&lt;&lt;');
			}
			else {
				$('.tag_synonims_'+$(this).attr('rel')).hide();
				$(this).attr('title','Показать синонимы');
				$(this).html('&gt;&gt;');
			}
		}
	});

	/* Post section end */

	/* Video section start */

	$(".show-description").live('click',function(event){
		event.preventDefault();
		if ($(this).parent().children("span").html() == "↓") {
			$(this).parent().children("span").html("↑");
			$(".description-"+$(this).attr('rel')).slideDown();
		}
		else {
			$(this).parent().children("span").html("↓");
			$(".description-"+$(this).attr('rel')).slideUp();
		}
	});

	/* Video section end */

	/* Art section start */

	$(".similar_navi").click(function(e){
		if (is_left_click(e)) {
			var new_src = $("div.image img").attr('src').split('/');
			new_src.pop(); new_src.pop();

			$(".loading_variation").css({'height':'auto','overflow':'auto'});

			var img_size = $(".booru_img").attr('rel');

			if (img_size == 'resized' && $(this).children(".variant_resized_link").length > 0) {
				var img_file = $(this).children(".variant_resized_link").html();
			} else {
				var img_file = $(this).children(".variant_link").html();
				img_size = 'full';
			}

			$(".similar_navi").removeClass('plaintext');
			$(this).addClass('plaintext');

			var img = new Image();
			$(img).load(new_src.join('/')+'/'+img_size+'/'+img_file, function () {
				$("div.image img").attr('src',new_src.join('/')+'/'+img_size+'/'+img_file);
				$(".loading_variation").css({'height':'0px','overflow':'hidden'});
			});
		}
	});

	$(".art_translation").easyTooltip();
	$("a.with_help3").easyTooltip({timeOut:1000});

	$('.booru_main img').unbind('mousemove');

	$(".show_art").live('click', function(){
		$(this).parents("div.show_nsfw_toggler").find(".hidden_art").show();
		$(this).parent(".art_not_showed").hide();
	});

	$(".toggle_show_art").live('click', function(){
		$.post(window.config.site_dir+"/ajax.php?m=cookie&f=set&field="+$(this).attr('rel')+"&val=1", function() {
			document.location.reload();
		});
	});

	$(".booru_show_toggle").live('click', function(event){
		if(is_left_click(event)) {
			if ($(".add_translation").length < 1) {
				if ($(this).is(".animated")) {
					var replace = '.gif';
				} else {
					var replace = '.jpg';
				}
				if ($(".booru_img").attr('rel') == 'full') {
					if ($(".similar_navi.plaintext").length == 0 || $(".similar_navi.plaintext .variant_resized_link").length == 1) {
						src = $(".booru_img img").attr('src').replace('/full/','/resized/').replace('.'+$(this).attr('rel'),replace);
					}
					$(".booru_img").attr('rel','resized');
					$("span.booru_show_full_container span").html('Изображение было уменьшено. ');
					$("a.booru_show_toggle").html('Показать в полном размере');
					$(".booru_img img").remove();
					$(".booru_img").append('<img src="'+src+'">');
				} else {
					src = $(".booru_img img").attr('src').replace('/resized/','/full/').replace(replace,'.'+$(this).attr('rel'));
					$(".booru_img").attr('rel','full');
					$("span.booru_show_full_container span").html('Полный размер. ');
					$("a.booru_show_toggle").html('Уменьшить изображение');
					$(".booru_img img").remove();
					$(".booru_img").append('<img src="'+src+'">');
				}
				$(".booru_main .image .art_translation").appendTo(".edit_field");
				$(".translations_hideout .art_translation").appendTo(".booru_main .image");
				$(".edit_field .art_translation").appendTo(".translations_hideout");
			}
		}
	});

	$(".booru_show_full_always").live('click', function(){
		$.post(window.config.site_dir+"/ajax.php?m=cookie&f=set&field=art.resized&val=0", function() {
			document.location.reload();
		});
	});

	$(".booru_translation_toggle").live('click', function(event){
		if(is_left_click(event)) {
			$(".image .art_translation").toggle();
		}
	});

	$(".vote_up:not(.inactive_vote)").click(function(event) {
		if (is_left_click(event)) {
			add_vote($(this).attr("rel"), 1);
		}
	});

	$(".vote_down:not(.inactive_vote)").click(function(event) {
		if (is_left_click(event)) {
			add_vote($(this).attr("rel"), -1);
		}
	});

	function add_vote (id, rating) {
		$(".vote_up, .vote_down").unbind("click");
		$.post(window.config.site_dir+"/ajax.php?m=art&f=set_vote&id="+id+"&rating="+rating, function() {
			$(".art_vote_wrapper img").addClass("inactive_vote");
			$(".art_vote_wrapper img").attr("title", 'Вы уже голосовали');
			var current_value = parseInt($(".art_vote_wrapper span").html());
			current_value = current_value + rating;
			$(".art_vote_wrapper span").html(current_value);
		});
	}

		/* Art - masstag9001 */

	$("#MassTag9001_sign").find('option:first').attr('selected', 'selected');
	$(".MassTag9001").val("");
	$(".MassTag9001").change(function(){window.masstag = true;});
	$("#MassTag9001_sign").change(function(){
		window.masstag = true;
		$(".MassTag9001").hide();
		$(".MassTag9001_"+$(this).find('option:selected').attr('rel')).show();
	});

	$("div.thumbnail").click(function(event){
		if (window.masstag && $(".MassTag9001:visible").val()) {
		 	event.preventDefault();
		 	$("#easyTooltip").remove();
			$(this).html('<img src="'+window.config.image_dir+'/ajax-loader.gif" style="margin-top:60px;">');
			$(this).load(window.config.site_dir+"/ajax.php?m=art&f=masstag&data="+urlencode($(".MassTag9001:visible").val())+"&sign="+$("#MassTag9001_sign").val()+"&id="+$(this).attr('rel'),
				function() { $("a.with_help3").unbind('easyTooltip').easyTooltip({ timeOut: 1000 });}
			);
		}
	});

		/* Art - pools */

	$(".check_closed").click(function(event){
		if ($("div#downscroller input.password").length == 1) {
			event.preventDefault(); window.type = this.nodeName;
			$.post(window.config.site_dir+"/ajax.php?m=add&f=checkpassword&val="+$("div#downscroller input.password").val()+"&id="+$("div#downscroller input.password").attr('rel'), function(data) {
				if (data != 'success') {
					$(".closed_group").slideDown();
				}
				else {
					if (window.type == "INPUT") {
						if ($(".delete_mode").attr('checked')) $(".delete_mode").removeAttr('checked');
						else $(".delete_mode").attr('checked','checked');
					}
				}
			});
		}
	});

	$(".booru_images a").click(function(event){
		if ($(".delete_mode").attr('checked')) {
			event.preventDefault();
			$.post(window.config.site_dir+"/ajax.php?m=edit&f=remove_from_pool&val="+$(this).attr('rel')+"&id="+$("div#downscroller input.password").attr('rel')+"&password="+$("div#downscroller input.password").val());
			$(this).remove();
		}
	});

	$(".save_pool_order").click(function(){
		if ($("div#downscroller input.password").length == 1) {
			$.post(window.config.site_dir+"/ajax.php?m=add&f=checkpassword&val="+$("div#downscroller input.password").val()+"&id="+$("div#downscroller input.password").attr('rel'), function(data) {
				if (data != 'success') {
					$(".closed_group").slideDown();
				}
				else {
					$(".booru_images").append('<form id="order"></form>');
					$(".booru_images a").each(function(){
						$("form#order").append('<input type="hidden" name="art[]" value="'+$(this).attr('rel')+'">')
					});
					var data = $("form#order").serialize();
					$.post(window.config.site_dir+"/ajax.php?m=edit&f=sort_pool&password="+$("div#downscroller input.password").val()+"&id="+$("div#downscroller input.password").attr('rel'), data, function(data) {
						 window.location = $(".save_pool_order").attr('href');
					});
				}
			});
		}
		else {
			$(".booru_images").append('<form id="order"></form>');
			$(".booru_images a").each(function(){
				$("form#order").append('<input type="hidden" name="art[]" value="'+$(this).attr('rel')+'">')
			});
			var data = $("form#order").serialize();
			$.post(window.config.site_dir+"/ajax.php?m=edit&f=sort_pool&password="+$("div#downscroller input.password").val()+"&id="+$("div#downscroller input.password").attr('rel'), data, function(data) {
				 window.location = $(".save_pool_order").attr('href');
			});
		}
	});

	/* Art section end */

	/* Search start */

	$("input.search").keyup(function(e){
		string = urlencode($(this).val().replace(/\//g," "));
		if (e.which != 38 && e.which != 40) {
			if ($("#search-tip").is(".search-main")) var index = '&index=1';
			else var index = '&index=0';
			$("#search-tip").load(window.config.site_dir+'/ajax.php?m=search&f=searchtip&data='+string+'&area='+$("input.search").attr('rel')+index);
		}
	});

	window.area = '';
	$(".searcharea:checked").each(function(){
		window.area = window.area + $(this).val();
	});
	$("input.search").attr('rel',window.area);

	$("input.search").keydown(function(e){
		switch (e.which) {
			case 40:
				b = parseInt($("#search-tip").attr('rel')); a = b + 1;
				if (!(a > $('.search-tip').size())) {
					$('.search-tip-'+b).parent().removeClass('active');
					$('.search-tip-'+a).parent().addClass('active');
					if ($('.search-tip-'+a).is(".tip-type-search")) {
						$(this).val($('.search-tip-'+a).attr('rel'));
					}
					else {
						$(this).val($('.search-tip-'+a).html().split(': ')[1]);
					}
					$("#search-tip").attr('rel',a);
				}
				break;
			case 38:
				b = parseInt($("#search-tip").attr('rel')); a = b - 1;
				if (!(a < 0)) {
					$('.search-tip-'+b).parent().removeClass('active');
					$('.search-tip-'+a).parent().addClass('active');
					$(this).val($('.search-tip-'+a).attr('rel'));
					$("#search-tip").attr('rel',a);
				}
				break;
			case 13:
				if ($(this).val().length > 2 && $(this).attr('rel').length > 0) {
					if ($(this).attr('rel') == 'a') sort_type = 'art';
					else sort_type = 'rel';
					document.location.href='/search/'+$(this).attr('rel')+'/'+sort_type+'/'+urlencode($(this).val().replace(/\//g," "))+'/';
				}
				break;
			default:
				break;
		}
	});

	$(".search-tip").live('click',function(event){
		if (event.button == 0) {
			if($(this).is(".tip-type-search")) {
				event.preventDefault();
				$("input.search").val($(this).attr('rel'));
				$("input.searchb").click();
			}
			else {
				document.location.href = $(this).attr('rel');
			}
		}
	});

	$("input.searchb").click(function(event){
		event.preventDefault();
		search_string = trim($("input.search").val().replace(/\//g," "));
		if (search_string.length > 2) {
			if ($("input.search").attr('rel') == 'a') sort_type = 'art';
			else sort_type = 'rel';
			if ($("input.search").attr('rel').length > 0)
				document.location.href='/search/'+$("input.search").attr('rel')+'/'+sort_type+'/'+urlencode(search_string)+'/';
			else
				alert('Задайте область поиска.');
		}
		else {
			alert('В строке поиска должно быть больше двух символов.');
		}
	});

	$("input.search_logs").keydown(function(e){
		switch (e.which) {
			case 13:
				if ($(this).val().length > 2) {
					document.location.href='/logs/search/'+urlencode($(this).val().replace(/\//g," "))+'/';
				}
				break;
			default:
				break;
		}
	});

	$("input.search_logs_button").click(function(event){
		event.preventDefault();
		search_string = trim($("input.search_logs").val().replace(/\//g," "));
		if (search_string.length > 2) {
			document.location.href='/logs/search/'+urlencode(search_string)+'/';
		} else {
			alert('В строке поиска должно быть больше двух символов.');
		}
	});

	$(".searcharea").change(function(){
		window.area = '';
		$(".searcharea:checked").each(function(){
			window.area = window.area + $(this).val();
		});
		$("input.search").attr('rel',window.area);
	});

	$(".search-options").click(function(){
		if ($(this).attr('rel') != 'open') {
			$(this).parent('td').children('div').slideDown();
			$(this).attr('rel','open');
			$(this).html('Спрятать опции поиска');
		}
		else {
			$(this).parent('td').children('div').slideUp();
			$(this).attr('rel','closed');
			$(this).html('Показать опции поиска');
		}
	});

	$(".secondary_searcharea").change(function(){
		window.area = '';
		$(".secondary_searcharea:checked").each(function(){
			window.area = window.area + $(this).val();
		});
		path = $(".secondary_search").attr("href").split('/');
		$(".secondary_search").attr("href",'/search/'+window.area+'/'+path[3]+'/'+path[4]+'/');
	});

	$(".show_searchareas").click(function(){
		if ($(this).attr('rel') != 'open') {
			$('.secondary_searchareas').slideDown();
			$(this).attr('rel','open');
			$(this).html('Спрятать область поиска.');
		}
		else {
			$('.secondary_searchareas').slideUp();
			$(this).attr('rel','closed');
			$(this).html('Изменить область поиска.');
		}
	});

	$(".search-switcher").change(function(){
		path = window.location.pathname.split('/');
		document.location.href='/search/'+path[2]+'/'+$(this).val()+'/'+path[4]+'/';
	});

	/* Search end */

	/* Board start */

	$(".delete_from_board").click(function(){
		if ($(this).parents('div').is(".thread")) {
			var type = "тред";
		} else {
			var type = "сообщение";
		}
		if (confirm("Вы уверены что хотите удалить "+type+" №"+$(this).attr('rel')+"?")) {
			$.post("/ajax.php?m=board&f=delete&id="+$(this).attr('rel'));
			window.location.reload();
		}
	});

	$(".switch_allboards").click(function(event){
		event.preventDefault();
		$.post(window.config.site_dir+"/ajax.php?m=cookie&f=set&field=board.allthreads&val="+$(this).attr('rel'), function() {
			document.location.reload();
		});
	});

	$(".always_embed_video").click(function(event){
		event.preventDefault();
		$.post(window.config.site_dir+"/ajax.php?m=cookie&f=set&field=board.embedvideo&val=1", function() {
			document.location.reload();
		});
	});

	$(".open_video").click(function(event){
		event.preventDefault();
		$(this).
			parent().
			html($("div#add_loader").html()).
			load(window.config.site_dir+"/ajax.php?m=board&f=load_video&id="+$(this).attr('rel')).
			css("border-color", "#FFFFFF");
	});

	$(".board_image_thumb").live('click', function(event){
		if(is_left_click(event)) {
			event.preventDefault();
			var sizes = $(this).attr('rel').split('x');
			var img = $(this).children('img');

			if (sizes[0] > 240) {
				img.css({'width':sizes[0]+'px','height':sizes[1]+'px'});
			}

			$(this).addClass('board_image_full').removeClass('board_image_thumb');
			$(this).parents('div').addClass('clear');
			if (img.attr('rel').indexOf('/full/') != -1) {
				var tmp = img.attr('rel');
				img.attr('rel', img.attr('src'));
				img.attr('src', tmp);
			}
			if(
				$(".board_image_thumb").length == 0 &&
				$(".board_unfold_all").length > 0
			) {
				var new_text = $(".board_unfold_all").attr('rel');
				var new_rel = $(".board_unfold_all").html();
				$(".board_unfold_all")
					.addClass('board_fold_all')
					.removeClass('board_unfold_all')
					.attr('rel', new_rel)
					.html(new_text);
			}
		}
	});

	$(".board_image_full").live('click', function(event){
		if(is_left_click(event)) {
			event.preventDefault();
			var img = $(this).children('img');
			img.css({'width':'auto','height':'auto'});
			$(this).addClass('board_image_thumb').removeClass('board_image_full');
			$(this).parents('div').removeClass('clear');
			if (img.attr('rel').indexOf('/thumbs/') != -1) {
				var tmp = img.attr('rel');
				img.attr('rel', img.attr('src'));
				img.attr('src', tmp);
			}
			if(
				$(".board_image_thumb").length > 0 &&
				$(".board_fold_all").length > 0
			) {
				var new_text = $(".board_fold_all").attr('rel');
				var new_rel = $(".board_fold_all").html();
				$(".board_fold_all")
					.addClass('board_unfold_all')
					.removeClass('board_fold_all')
					.attr('rel', new_rel)
					.html(new_text);
			}
		}
	});

	$(".board_unfold_all").live('click', function(event){
		event.preventDefault();
		if(is_left_click(event)) {
			$(".board_image_thumb").click();
		}
	});

	$(".board_fold_all").live('click', function(event){
		event.preventDefault();
		if(is_left_click(event)) {
			$(".board_image_full").click();
		}
	});

	$("a.board_download").click(function(event){
		event.preventDefault();
		if(is_left_click(event)) {
			var data = $(this).attr('href').split('-');
			$.download('/ajax.php','m=board&f=download&thread='+data[1]+'&type='+data[2]);
		}
	});

	/* Board end */

	if ($(".similar_navi").length > 0) {
		var current = document.location.hash.split('#')[1];
		if (current == undefined) current = 1;
		$(".similar_navi_"+current).click();
	}

});

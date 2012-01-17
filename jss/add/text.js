function insert(start, end, id) {
	element = document.getElementById(id);
	if (document.selection) {
		element.focus();
		sel = document.selection.createRange();
		sel.text = start + sel.text + end;
	}
	else if (element.selectionStart || element.selectionStart == '0') {
		element.focus();
		var startPos = element.selectionStart;
		var endPos = element.selectionEnd;
		element.value = element.value.substring(0, startPos) + start + element.value.substring(startPos, endPos) + end + element.value.substring(endPos, element.value.length);
	}
	else {
		element.value += start + end;
	}
}

(function($) {
	$.fn.easyTooltip = function(options){
		var defaults = {
			xOffset: 10,
			yOffset: 25,
			tooltipId: "easyTooltip",
			clickRemove: false,
			content: "",
			useElement: "",
			timeOut: false
		};
		var options = $.extend(defaults, options);
		var content;

		this.each(function() {
			var title = $(this).attr("title");
			$(this).hover(function(e){
				$(this).attr("title","")
				content = (options.content != "") ? options.content : title;
				content = (options.useElement != "") ? $("#" + options.useElement).html() : content;;
				if (content != "" && content != undefined){
					$("body").append("<div id='"+ options.tooltipId +"'>"+ content +"</div>");
					if (options.timeOut) {
						$("#" + options.tooltipId)
							.css("position","absolute")
							.css("top",(e.pageY - options.yOffset) + "px")
							.css("left",(e.pageX + options.xOffset) + "px")
							.css("display","none");
							setTimeout( function() {	$("#" + options.tooltipId).fadeIn("fast")			}, options.timeOut);
					}
					else {
						$("#" + options.tooltipId)
							.css("position","absolute")
							.css("top",(e.pageY - options.yOffset) + "px")
							.css("left",(e.pageX + options.xOffset) + "px")
							.css("display","none").fadeIn("fast")
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

$(document).ready(function(){

	$("img.bb").easyTooltip();

	$('img.bb').click(function() {
		var getimage='';var urltext='';var attribs='';
		if ($(this).attr('rel') == 'url') {
			attribs = prompt('Адрес ссылки, полностью', 'http://');
			if (attribs == '' || attribs == null) return false;
			else attribs = '='+attribs;
			if (attribs == "=null") return false;
			element = document.getElementById('textfield');
			if (element.selectionStart == element.selectionEnd) {
				urltext = prompt('Текст ссылки', '');
				if (urltext == null) return false;
			}
		}
		if ($(this).attr('rel') == 'img') {
			getimage = prompt('Адрес картинки, полностью', 'http://');
			if (getimage == '' || getimage == null) return false;
			attribs = '='+prompt('Уменьшить пропорционально до ширины, в пикселях', '400');
			if (attribs == "=null") return false;
		}
		if ($(this).attr('rel') == 'spoiler') {
			attribs = '='+prompt('Заголовок для спойлера', '');
			if (attribs == "=null") return false;
		}
		var start = '['+$(this).attr('rel')+attribs+']'+urltext;
		var end = '[/'+$(this).attr('rel')+']';
		if ($(this).attr('rel') != 'img') insert(start, end, 'textfield');
		else $("#textfield").val($("#textfield").val() + start + getimage + end);
		return false;
	});

	$("#comment-main a").click(function(event){
		event.preventDefault();
		$('#comments').append($('#comments-field'));
		$("#comment-parent").val('0');
		$("#comment-main").hide();
		$(".commentsh2").show();
	});

});

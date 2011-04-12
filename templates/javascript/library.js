jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') {
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString();
        }
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else {
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};

(function($) {
	$.fn.easyTooltip = function(options){	  
		var defaults = {	
			xOffset: 10,		
			yOffset: 25,
			tooltipId: "easyTooltip",
			clickRemove: false,
			content: "",
			useAttribute: "",
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
				content = (options.useAttribute != "") ? $(this).attr(options.useAttribute) : content;
				if (content != "" && content != undefined){			
					$("body").append("<div id='"+ options.tooltipId +"'>"+ content +"</div>");
					var left_margin = ($(document).width() > e.pageX * 2) ?
						e.pageX + options.xOffset :
						e.pageX - options.xOffset - 20 - $('#'+options.tooltipId).width();
					if (options.timeOut) {
						$("#" + options.tooltipId)
							.css("position","absolute")
							.css("top",(e.pageY - options.yOffset) + "px")
							.css("left",left_margin + "px")						
							.css("display","none");
							setTimeout(function() {$("#" + options.tooltipId).fadeIn("fast")}, options.timeOut);	
					} else {
						$("#" + options.tooltipId)
							.css("position","absolute")
							.css("top",(e.pageY - options.yOffset) + "px")
							.css("left",left_margin + "px")						
							.css("display","none").fadeIn("fast");
					}
				}
			},
			function(){	
				$("#" + options.tooltipId).remove();
				$(this).attr("title",title);
			});	
			$(this).mousemove(function(e){
				var left_margin = ($(document).width() > e.pageX * 2) ?
					e.pageX + options.xOffset :
					e.pageX - options.xOffset - 20 - $('#'+options.tooltipId).width();				
				$("#" + options.tooltipId)
					.css("top",(e.pageY - options.yOffset) + "px")
					.css("left",left_margin + "px")					
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

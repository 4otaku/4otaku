function urlencode (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').
                                                                    replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}

(function ($) {
	$.event.special.load = {
		setup: function(data, namespaces, hollaback) {
			var retVal = false;
			if (this.tagName.toLowerCase() === 'img' && this.src !== "") {
				if (this.complete || this.readyState === 4) {
					$(this).bind('load', data || {}, hollaback).trigger('load');
					retVal = true;
				}
				else if (this.readyState === 'uninitialized' && this.src.indexOf('data:') >= 0) {
					$(this).trigger('error');
					retVal = true;
				}
			}
			return retVal;
		}
	}
}(jQuery));

jQuery.fn.extend({
	everyTime: function(interval, label, fn, times) {
		return this.each(function() {
			jQuery.timer.add(this, interval, label, fn, times);
		});
	},
	oneTime: function(interval, label, fn) {
		return this.each(function() {
			jQuery.timer.add(this, interval, label, fn, 1);
		});
	},
	stopTime: function(label, fn) {
		return this.each(function() {
			jQuery.timer.remove(this, label, fn);
		});
	}
});

jQuery.extend({
	timer: {
		global: [],
		guid: 1,
		dataKey: "jQuery.timer",
		regex: /^([0-9]+(?:\.[0-9]*)?)\s*(.*s)?$/,
		powers: {'ms': 1},
		timeParse: function(value) {
			if (value == undefined || value == null)
				return null;
			var result = this.regex.exec(jQuery.trim(value.toString()));
			if (result[2]) {
				var num = parseFloat(result[1]);
				var mult = this.powers[result[2]] || 1;
				return num * mult;
			} else {
				return value;
			}
		},
		add: function(element, interval, label, fn, times) {
			var counter = 0;			
			if (jQuery.isFunction(label)) {
				if (!times) 
					times = fn;
				fn = label;
				label = interval;
			}			
			interval = jQuery.timer.timeParse(interval);
			if (typeof interval != 'number' || isNaN(interval) || interval < 0)
				return;
			if (typeof times != 'number' || isNaN(times) || times < 0) 
				times = 0;			
			times = times || 0;			
			var timers = jQuery.data(element, this.dataKey) || jQuery.data(element, this.dataKey, {});			
			if (!timers[label])
				timers[label] = {};			
			fn.timerID = fn.timerID || this.guid++;			
			var handler = function() {
				if ((++counter > times && times !== 0) || fn.call(element, counter) === false)
					jQuery.timer.remove(element, label, fn);
			};			
			handler.timerID = fn.timerID;			
			if (!timers[label][fn.timerID])
				timers[label][fn.timerID] = window.setInterval(handler,interval);			
			this.global.push( element );			
		},
		remove: function(element, label, fn) {
			var timers = jQuery.data(element, this.dataKey), ret;			
			if ( timers ) {				
				if (!label) {
					for ( label in timers )
						this.remove(element, label, fn);
				} else if ( timers[label] ) {
					if ( fn ) {
						if ( fn.timerID ) {
							window.clearInterval(timers[label][fn.timerID]);
							delete timers[label][fn.timerID];
						}
					} else {
						for ( var fn in timers[label] ) {
							window.clearInterval(timers[label][fn]);
							delete timers[label][fn];
						}
					}					
					for ( ret in timers[label] ) break;
					if ( !ret ) {
						ret = null;
						delete timers[label];
					}
				}				
				for ( ret in timers ) break;
				if ( !ret ) 
					jQuery.removeData(element, this.dataKey);
			}
		}
	}
});

jQuery(window).bind("unload", function() {
	jQuery.each(jQuery.timer.global, function(index, item) {
		jQuery.timer.remove(item);
	});
});

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
				if (!window.tooltip) {
					window.tooltip = true;
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
				}
			},
			function(){	
				$("#" + options.tooltipId).remove();
				window.tooltip = false;
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

function get_image(number,show){
	var slideurl = document.location.pathname.split('/');
	if (number == 1) $(".body").stopTime("get_backward");
	$.post("/ajax.php?m=art&f=slideshow&type="+slideurl[3]+"&area="+urlencode(slideurl[4].split('#')[0])+"&id="+number, function(data) {
		if (data != 'finish') {
			window.has_images = true;
			if (show) $(".body").append(data).children("#art-"+document.location.hash.split('#')[1]).css({'display' : ''});
			else $(".body").append(data);
			$(".art_translation").unbind('easyTooltip').easyTooltip();
			$(".body .image").each(function(){
				if ($("[id="+this.id+"]").length > 1) $("[id="+this.id+"]:last").remove();
			});
		} else {
			if (window.has_images != true && $(".body").html().length < 10) {
				$(".body").html("<h2>По выбранному вами адресу нет изображений.</h2>");
				window.slideshow_error = true;
			}
			$(".body").stopTime("get_forward");
		}
		if ($("#resize:checked").length > 0) resize_images();
		window.getting = false;	
	});
}

function toggle_image(current, direction, e) {
	if ((!$.browser.msie && e.button == 0) || ($.browser.msie && e.button == 1) || e == 'skip') {
		var num = current + direction;
		if ($("#art-"+num).length > 0) {
			$("#art-"+num).css({'display' : ''});
			$("#art-"+current).hide();
			document.location.hash = "#"+num;
			$(".body").attr('rel',document.location.hash);
		}
	}
}

function resize_images() {
	$(".image").each(function() {
		if ($(this).children('img').height() > ($(window).height() - 58)) {
			var coeff = ($(window).height() - 60)/$(this).children('img').attr('rel');
			$(this).children('img').height(($(window).height() - 60) + 'px');
			$(this).children('.art_translation').each(function(){
				var def = $(this).attr('rel').split(':');
				$(this).css({'width' : Math.round(def[0]*coeff)+'px', 'height' : Math.round(def[1]*coeff)+'px', 'left' : Math.round(def[2]*coeff)+'px', 'top' : Math.round(def[3]*coeff)+'px'});
			});
		}
		else if ($(this).children('img').height() < ($(window).height() - 62)) {
			var coeff = Math.min(($(window).height() - 60),$(this).children('img').attr('rel'))/$(this).children('img').attr('rel');
			$(this).children('img').height(Math.round($(this).children('img').attr('rel')*coeff) + 'px');
			$(this).children('.art_translation').each(function(){
				var def = $(this).attr('rel').split(':');
				$(this).css({'width' : Math.round(def[0]*coeff)+'px', 'height' : Math.round(def[1]*coeff)+'px', 'left' : Math.round(def[2]*coeff)+'px', 'top' : Math.round(def[3]*coeff)+'px'});
			});				
		}				
	});
}

$(document).ready(function(){
	
	$(".body").everyTime(100, "check_image", function() {
		var current = parseInt($(this).attr('rel').split('#')[1]);
		if ("#"+current != document.location.hash) {
			$(this).attr('rel',document.location.hash);
			$(".image").hide();
			$("#art-"+document.location.hash.split('#')[1]).css({'display' : ''});
			if ($("#art-"+document.location.hash.split('#')[1]).length == 0) get_image(document.location.hash.split('#')[1],true);
		}
		if ($("#resize:checked").length > 0) resize_images();
		if (window.slideshow_error == true) {
			$(".arrow_right").hide();
			$(".arrow_left").hide();
		} else {
			if ($("#art-"+(current+1)).length == 0) $(".arrow_right").hide(); else $(".arrow_right").show();
			if (current == 1) $(".arrow_left").hide(); else $(".arrow_left").show();
		}
	});
		
	$(".body").everyTime(500, "get_forward", function() {
		var current = parseInt($(this).attr('rel').split('#')[1]);
		if (!window.getting && $("#art-"+(current+1)+",#art-"+(current+2)+",#art-"+(current+3)).length < 3) {
			var last;
			if ($("#art-"+(current+3)).length > 0) last = current;
			else if ($("#art-"+(current+2)).length > 0) last = current + 2;
			else if ($("#art-"+(current+1)).length > 0) last = current + 1;
			else last = current;
			window.getting = true;
			get_image(last + 1,false);			
		}
	});	
		
	$(".body").everyTime(500, "get_backward", function() {
		var current = parseInt($(this).attr('rel').split('#')[1]);
		if (!window.getting && $("#art-"+(current-1)).length < 1) {
			window.getting = true;
			get_image(Math.max(current-5,1),false);			
		}
	});	
	
	if (document.location.hash == '') document.location.hash = '#1';
	window.getting = true;
	get_image(document.location.hash.split('#')[1],true);
	$(".body").attr('rel',document.location.hash);
	
	if ($("#auto:checked").length == 1) {
		$(".body").everyTime(parseInt($("#delay").val())*1000, "slideshow", function(){
			toggle_image(parseInt($('.image:visible').attr('id').split('-')[1]),1,'skip');
		});
	}	
	
	$(".image").live('click',function(e){
		toggle_image(parseInt($(this).attr('id').split('-')[1]),1,e);
	});
	
	$(".arrow_right").click(function(e){
		toggle_image(parseInt($('.image:visible').attr('id').split('-')[1]),1,e);
	});		
	
	$(".arrow_left").click(function(e){
		toggle_image(parseInt($('.image:visible').attr('id').split('-')[1]),-1,e);
	});		
	
	$("#resize").change(function(){
		$.post('/ajax.php?m=cookie&f=set&field=slideshow.resize&val={'+$(this).is(':checked')+'}');
		if ($("#resize:checked").length == 0) {
			$(".image img").each(function(){ 
				$(this).height($(this).attr('rel') + 'px'); 
			});
			$(".image .art_translation").each(function(){
				var def = $(this).attr('rel').split(':');
				$(this).css({'width' : def[0]+'px', 'height' : def[1]+'px', 'left' : def[2]+'px', 'top' : def[3]+'px'});
			});				
		}			
	});
	
	$("#auto").change(function(){
		$.post('/ajax.php?m=cookie&f=set&field=slideshow.auto&val={'+$(this).is(':checked')+'}');
		if ($("#auto:checked").length == 0) {
			$('.delay_holder').hide();
			$(".body").stopTime("slideshow");
		}
		else {
			$('.delay_holder').show();
			$(".body").everyTime(parseInt($("#delay").val())*1000, "slideshow", function(){
				toggle_image(parseInt($('.image:visible').attr('id').split('-')[1]),1,'skip');
			});
		}
	});	
	
	$("#delay").keyup(function(){
		var num = parseInt($(this).val());
		if (num && num < 120) {
			$.post('/ajax.php?m=cookie&f=set&field=slideshow.delay&val='+$(this).val());
			$(".body").stopTime("slideshow");
			$(".body").everyTime(num*1000, "slideshow", function(){
				toggle_image(parseInt($('.image:visible').attr('id').split('-')[1]),1,'skip');
			});			
		}
	});	
});

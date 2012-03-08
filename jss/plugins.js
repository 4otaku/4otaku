/*
 * Thickbox 3 - One Box To Rule Them All.
 * By Cody Lindley (http://www.codylindley.com)
 * Copyright (c) 2007 cody lindley
 * Licensed under the MIT License: http://www.opensource.org/licenses/mit-license.php
*/

var tb_pathToImage = window.config.image_dir+"/loadingAnimation.gif";

eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('$(o).2S(9(){1u(\'a.18, 3n.18, 3i.18\');1w=1p 1t();1w.L=2H});9 1u(b){$(b).s(9(){6 t=X.Q||X.1v||M;6 a=X.u||X.23;6 g=X.1N||P;19(t,a,g);X.2E();H P})}9 19(d,f,g){3m{3(2t o.v.J.2i==="2g"){$("v","11").r({A:"28%",z:"28%"});$("11").r("22","2Z");3(o.1Y("1F")===M){$("v").q("<U 5=\'1F\'></U><4 5=\'B\'></4><4 5=\'8\'></4>");$("#B").s(G)}}n{3(o.1Y("B")===M){$("v").q("<4 5=\'B\'></4><4 5=\'8\'></4>");$("#B").s(G)}}3(1K()){$("#B").1J("2B")}n{$("#B").1J("2z")}3(d===M){d=""}$("v").q("<4 5=\'K\'><1I L=\'"+1w.L+"\' /></4>");$(\'#K\').2y();6 h;3(f.O("?")!==-1){h=f.3l(0,f.O("?"))}n{h=f}6 i=/\\.2s$|\\.2q$|\\.2m$|\\.2l$|\\.2k$/;6 j=h.1C().2h(i);3(j==\'.2s\'||j==\'.2q\'||j==\'.2m\'||j==\'.2l\'||j==\'.2k\'){1D="";1G="";14="";1z="";1x="";R="";1n="";1r=P;3(g){E=$("a[@1N="+g+"]").36();25(D=0;((D<E.1c)&&(R===""));D++){6 k=E[D].u.1C().2h(i);3(!(E[D].u==f)){3(1r){1z=E[D].Q;1x=E[D].u;R="<1e 5=\'1X\'>&1d;&1d;<a u=\'#\'>2T &2R;</a></1e>"}n{1D=E[D].Q;1G=E[D].u;14="<1e 5=\'1U\'>&1d;&1d;<a u=\'#\'>&2O; 2N</a></1e>"}}n{1r=1b;1n="1t "+(D+1)+" 2L "+(E.1c)}}}S=1p 1t();S.1g=9(){S.1g=M;6 a=2x();6 x=a[0]-1M;6 y=a[1]-1M;6 b=S.z;6 c=S.A;3(b>x){c=c*(x/b);b=x;3(c>y){b=b*(y/c);c=y}}n 3(c>y){b=b*(y/c);c=y;3(b>x){c=c*(x/b);b=x}}13=b+30;1a=c+2G;$("#8").q("<a u=\'\' 5=\'1L\' Q=\'1o\'><1I 5=\'2F\' L=\'"+f+"\' z=\'"+b+"\' A=\'"+c+"\' 23=\'"+d+"\'/></a>"+"<4 5=\'2D\'>"+d+"<4 5=\'2C\'>"+1n+14+R+"</4></4><4 5=\'2A\'><a u=\'#\' 5=\'Z\' Q=\'1o\'>1l</a> 1k 1j 1s</4>");$("#Z").s(G);3(!(14==="")){9 12(){3($(o).N("s",12)){$(o).N("s",12)}$("#8").C();$("v").q("<4 5=\'8\'></4>");19(1D,1G,g);H P}$("#1U").s(12)}3(!(R==="")){9 1i(){$("#8").C();$("v").q("<4 5=\'8\'></4>");19(1z,1x,g);H P}$("#1X").s(1i)}o.1h=9(e){3(e==M){I=2w.2v}n{I=e.2u}3(I==27){G()}n 3(I==3k){3(!(R=="")){o.1h="";1i()}}n 3(I==3j){3(!(14=="")){o.1h="";12()}}};16();$("#K").C();$("#1L").s(G);$("#8").r({Y:"T"})};S.L=f}n{6 l=f.2r(/^[^\\?]+\\??/,\'\');6 m=2p(l);13=(m[\'z\']*1)+30||3h;1a=(m[\'A\']*1)+3g||3f;W=13-30;V=1a-3e;3(f.O(\'2j\')!=-1){1E=f.1B(\'3d\');$("#15").C();3(m[\'1A\']!="1b"){$("#8").q("<4 5=\'2f\'><4 5=\'1H\'>"+d+"</4><4 5=\'2e\'><a u=\'#\' 5=\'Z\' Q=\'1o\'>1l</a> 1k 1j 1s</4></4><U 1W=\'0\' 2d=\'0\' L=\'"+1E[0]+"\' 5=\'15\' 1v=\'15"+1f.2c(1f.1y()*2b)+"\' 1g=\'1m()\' J=\'z:"+(W+29)+"p;A:"+(V+17)+"p;\' > </U>")}n{$("#B").N();$("#8").q("<U 1W=\'0\' 2d=\'0\' L=\'"+1E[0]+"\' 5=\'15\' 1v=\'15"+1f.2c(1f.1y()*2b)+"\' 1g=\'1m()\' J=\'z:"+(W+29)+"p;A:"+(V+17)+"p;\'> </U>")}}n{3($("#8").r("Y")!="T"){3(m[\'1A\']!="1b"){$("#8").q("<4 5=\'2f\'><4 5=\'1H\'>"+d+"</4><4 5=\'2e\'><a u=\'#\' 5=\'Z\'>1l</a> 1k 1j 1s</4></4><4 5=\'F\' J=\'z:"+W+"p;A:"+V+"p\'></4>")}n{$("#B").N();$("#8").q("<4 5=\'F\' 3c=\'3b\' J=\'z:"+W+"p;A:"+V+"p;\'></4>")}}n{$("#F")[0].J.z=W+"p";$("#F")[0].J.A=V+"p";$("#F")[0].3a=0;$("#1H").11(d)}}$("#Z").s(G);3(f.O(\'37\')!=-1){$("#F").q($(\'#\'+m[\'26\']).1T());$("#8").24(9(){$(\'#\'+m[\'26\']).q($("#F").1T())});16();$("#K").C();$("#8").r({Y:"T"})}n 3(f.O(\'2j\')!=-1){16();3($.1q.35){$("#K").C();$("#8").r({Y:"T"})}}n{$("#F").34(f+="&1y="+(1p 33().32()),9(){16();$("#K").C();1u("#F a.18");$("#8").r({Y:"T"})})}}3(!m[\'1A\']){o.21=9(e){3(e==M){I=2w.2v}n{I=e.2u}3(I==27){G()}}}}31(e){}}9 1m(){$("#K").C();$("#8").r({Y:"T"})}9 G(){$("#2Y").N("s");$("#Z").N("s");$("#8").2X("2W",9(){$(\'#8,#B,#1F\').2V("24").N().C()});$("#K").C();3(2t o.v.J.2i=="2g"){$("v","11").r({A:"1Z",z:"1Z"});$("11").r("22","")}o.1h="";o.21="";H P}9 16(){$("#8").r({2U:\'-\'+20((13/2),10)+\'p\',z:13+\'p\'});3(!(1V.1q.2Q&&1V.1q.2P<7)){$("#8").r({38:\'-\'+20((1a/2),10)+\'p\'})}}9 2p(a){6 b={};3(!a){H b}6 c=a.1B(/[;&]/);25(6 i=0;i<c.1c;i++){6 d=c[i].1B(\'=\');3(!d||d.1c!=2){39}6 e=2a(d[0]);6 f=2a(d[1]);f=f.2r(/\\+/g,\' \');b[e]=f}H b}9 2x(){6 a=o.2M;6 w=1S.2o||1R.2o||(a&&a.1Q)||o.v.1Q;6 h=1S.1P||1R.1P||(a&&a.2n)||o.v.2n;1O=[w,h];H 1O}9 1K(){6 a=2K.2J.1C();3(a.O(\'2I\')!=-1&&a.O(\'3o\')!=-1){H 1b}}',62,211,'|||if|div|id|var||TB_window|function||||||||||||||else|document|px|append|css|click||href|body||||width|height|TB_overlay|remove|TB_Counter|TB_TempArray|TB_ajaxContent|tb_remove|return|keycode|style|TB_load|src|null|unbind|indexOf|false|title|TB_NextHTML|imgPreloader|block|iframe|ajaxContentH|ajaxContentW|this|display|TB_closeWindowButton||html|goPrev|TB_WIDTH|TB_PrevHTML|TB_iframeContent|tb_position||thickbox|tb_show|TB_HEIGHT|true|length|nbsp|span|Math|onload|onkeydown|goNext|клавиша|или|закрыть|tb_showIframe|TB_imageCount|Close|new|browser|TB_FoundURL|Esc|Image|tb_init|name|imgLoader|TB_NextURL|random|TB_NextCaption|modal|split|toLowerCase|TB_PrevCaption|urlNoQuery|TB_HideSelect|TB_PrevURL|TB_ajaxWindowTitle|img|addClass|tb_detectMacXFF|TB_ImageOff|150|rel|arrayPageSize|innerHeight|clientWidth|self|window|children|TB_prev|jQuery|frameborder|TB_next|getElementById|auto|parseInt|onkeyup|overflow|alt|unload|for|inlineId||100||unescape|1000|round|hspace|TB_closeAjaxWindow|TB_title|undefined|match|maxHeight|TB_iframe|bmp|gif|png|clientHeight|innerWidth|tb_parseQuery|jpeg|replace|jpg|typeof|which|keyCode|event|tb_getPageSize|show|TB_overlayBG|TB_closeWindow|TB_overlayMacFFBGHack|TB_secondLine|TB_caption|blur|TB_Image|60|tb_pathToImage|mac|userAgent|navigator|of|documentElement|Prev|lt|version|msie|gt|ready|Next|marginLeft|trigger|fast|fadeOut|TB_imageOff|hidden||catch|getTime|Date|load|safari|get|TB_inline|marginTop|continue|scrollTop|TB_modal|class|TB_|45|440|40|630|input|188|190|substr|try|area|firefox'.split('|'),0,{}))

/* Jquery cookie */

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

/* Jquery tooltip */

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

/* Jquery download */

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

/**
 * Storage plugin
 * Provides a simple interface for storing data such as user preferences.
 * Storage is useful for saving and retreiving data from the user's browser.
 * For newer browsers, localStorage is used.
 * If localStorage isn't supported, then cookies are used instead.
 * Retrievable data is limited to the same domain as this file.
 *
 * Usage:
 * This plugin extends jQuery by adding itself as a static method.
 * $.Storage - is the class name, which represents the user's data store, whether it's cookies or local storage.
 *             <code>if ($.Storage)</code> will tell you if the plugin is loaded.
 * $.Storage.set("name", "value") - Stores a named value in the data store.
 * $.Storage.set({"name1":"value1", "name2":"value2", etc}) - Stores multiple name/value pairs in the data store.
 * $.Storage.get("name") - Retrieves the value of the given name from the data store.
 * $.Storage.remove("name") - Permanently deletes the name/value pair from the data store.
 *
 * @author Dave Schindler
 *
 * Distributed under the MIT License
 *
 * Copyright (c) 2010 Dave Schindler
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
(function($) {
	// Private data
	var isLS=typeof window.localStorage!=='undefined';
	// Private functions
	function wls(n,v){var c;if(typeof n==="string"&&typeof v==="string"){localStorage[n]=v;return true;}else if(typeof n==="object"&&typeof v==="undefined"){for(c in n){if(n.hasOwnProperty(c)){localStorage[c]=n[c];}}return true;}return false;}
	function wc(n,v){var dt,e,c;dt=new Date();dt.setTime(dt.getTime()+31536000000);e="; expires="+dt.toGMTString();if(typeof n==="string"&&typeof v==="string"){document.cookie=n+"="+v+e+"; path=/";return true;}else if(typeof n==="object"&&typeof v==="undefined"){for(c in n) {if(n.hasOwnProperty(c)){document.cookie=c+"="+n[c]+e+"; path=/";}}return true;}return false;}
	function rls(n){return localStorage[n];}
	function rc(n){var nn, ca, i, c;nn=n+"=";ca=document.cookie.split(';');for(i=0;i<ca.length;i++){c=ca[i];while(c.charAt(0)===' '){c=c.substring(1,c.length);}if(c.indexOf(nn)===0){return c.substring(nn.length,c.length);}}return null;}
	function dls(n){return delete localStorage[n];}
	function dc(n){return wc(n,"",-1);}

	/**
	* Public API
	* $.Storage - Represents the user's data store, whether it's cookies or local storage.
	* $.Storage.set("name", "value") - Stores a named value in the data store.
	* $.Storage.set({"name1":"value1", "name2":"value2", etc}) - Stores multiple name/value pairs in the data store.
	* $.Storage.get("name") - Retrieves the value of the given name from the data store.
	* $.Storage.remove("name") - Permanently deletes the name/value pair from the data store.
	*/
	$.extend({
		Storage: {
			set: isLS ? wls : wc,
			get: isLS ? rls : rc,
			remove: isLS ? dls :dc
		}
	});
})(jQuery);

// Chosen, a Select Box Enhancer for jQuery and Protoype
// by Patrick Filler for Harvest, http://getharvest.com
//
// Version 0.9.5
// Full source at https://github.com/harvesthq/chosen
// Copyright (c) 2011 Harvest http://getharvest.com

// MIT License, https://github.com/harvesthq/chosen/blob/master/LICENSE.md
// This file is generated by `cake build`, do not edit it by hand.
/*
Chosen source: generate output using 'cake build'
Copyright (c) 2011 by Harvest
*/
var $, AbstractChosen, Chosen, SelectParser, get_side_border_padding, root;
var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; }, __hasProp = Object.prototype.hasOwnProperty, __extends = function(child, parent) {
  for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; }
  function ctor() { this.constructor = child; }
  ctor.prototype = parent.prototype;
  child.prototype = new ctor;
  child.__super__ = parent.prototype;
  return child;
};
root = this;
AbstractChosen = (function() {
  function AbstractChosen(form_field, options) {
    this.form_field = form_field;
    this.options = options != null ? options : {};
    this.set_default_values();
    this.is_multiple = this.form_field.multiple;
    this.default_text_default = this.is_multiple ? "Select Some Options" : "Select an Option";
    this.setup();
    this.set_up_html();
    this.register_observers();
    this.finish_setup();
  }
  AbstractChosen.prototype.set_default_values = function() {
    this.activate_action = __bind(function(evt) {
      return this.activate_field(evt);
    }, this);
    this.active_field = false;
    this.mouse_on_container = false;
    this.results_showing = false;
    this.result_highlighted = null;
    this.result_single_selected = null;
    this.allow_single_deselect = (this.options.allow_single_deselect != null) && this.form_field.options[0].text === "" ? this.options.allow_single_deselect : false;
    this.disable_search_threshold = this.options.disable_search_threshold || 0;
    this.choices = 0;
    return this.results_none_found = this.options.no_results_text || "Ничего не найдено по";
  };
  AbstractChosen.prototype.mouse_enter = function() {
    return this.mouse_on_container = true;
  };
  AbstractChosen.prototype.mouse_leave = function() {
    return this.mouse_on_container = false;
  };
  AbstractChosen.prototype.input_focus = function(evt) {
    if (!this.active_field) {
      return setTimeout((__bind(function() {
        return this.container_mousedown();
      }, this)), 50);
    }
  };
  AbstractChosen.prototype.input_blur = function(evt) {
    if (!this.mouse_on_container) {
      this.active_field = false;
      return setTimeout((__bind(function() {
        return this.blur_test();
      }, this)), 100);
    }
  };
  AbstractChosen.prototype.result_add_option = function(option) {
    var classes, style;
    if (!option.disabled) {
      option.dom_id = this.container_id + "_o_" + option.array_index;
      classes = [];
      if (option.selected) {
        classes.push("result-selected");
      }
      if (option.group_array_index != null) {
        classes.push("group-option");
      }
      if (option.classes !== "") {
        classes.push(option.classes);
      }
      style = option.style.cssText !== "" ? " style=\"" + option.style + "\"" : "";
      return '<li id="' + option.dom_id + '" class="' + classes.join(' ') + '"' + style + '>' + option.html + '</li>';
    } else {
      return "";
    }
  };
  AbstractChosen.prototype.results_update_field = function() {
    this.result_clear_highlight();
    this.result_single_selected = null;
    return this.results_build();
  };
  AbstractChosen.prototype.results_toggle = function() {
    if (this.results_showing) {
      return this.results_hide();
    } else {
      return this.results_show();
    }
  };
  AbstractChosen.prototype.results_search = function(evt) {
    if (this.results_showing) {
      return this.winnow_results();
    } else {
      return this.results_show();
    }
  };
  AbstractChosen.prototype.keyup_checker = function(evt) {
    var stroke, _ref;
    stroke = (_ref = evt.which) != null ? _ref : evt.keyCode;
    this.search_field_scale();
    switch (stroke) {
      case 27:
        if (this.results_showing) {
          return this.results_hide();
        }
        break;
      case 9:
      case 38:
      case 40:
      case 16:
      case 91:
      case 17:
        break;
      default:
        return this.results_search();
    }
  };
  AbstractChosen.prototype.generate_field_id = function() {
    var new_id;
    new_id = this.generate_random_id();
    this.form_field.id = new_id;
    return new_id;
  };
  AbstractChosen.prototype.generate_random_char = function() {
    var chars, newchar, rand;
    chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZ";
    rand = Math.floor(Math.random() * chars.length);
    return newchar = chars.substring(rand, rand + 1);
  };
  return AbstractChosen;
})();
root.AbstractChosen = AbstractChosen;
SelectParser = (function() {
  function SelectParser() {
    this.options_index = 0;
    this.parsed = [];
    this.tree = {};
  }
  SelectParser.prototype.add_node = function(child) {
    if (child.nodeName === "OPTGROUP") {
      return this.add_group(child);
    } else {
      return this.add_option(child);
    }
  };
  SelectParser.prototype.add_group = function(group) {
    var group_position, option, _i, _len, _ref, _results;
    group_position = this.parsed.length;
    this.parsed.push({
      array_index: group_position,
      group: true,
      label: group.label,
      children: 0,
      disabled: group.disabled
    });
    _ref = group.childNodes;
    _results = [];
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      option = _ref[_i];
      _results.push(this.add_option(option, group_position, group.disabled));
    }
    return _results;
  };
  SelectParser.prototype.add_option = function(option, group_position, group_disabled) {
    if (option.nodeName === "OPTION") {
      if (option.text !== "") {
        if (group_position != null) {
          this.parsed[group_position].children += 1;
        }
        this.parsed.push({
          array_index: this.parsed.length,
          options_index: this.options_index,
          value: option.value,
          text: option.text,
          html: option.innerHTML,
          selected: option.selected,
          disabled: group_disabled === true ? group_disabled : option.disabled,
          group_array_index: group_position,
          classes: option.className,
          style: option.style.cssText
        });
        this.push_tree(option.value);
      } else {
        this.parsed.push({
          array_index: this.parsed.length,
          options_index: this.options_index,
          empty: true
        });
      }
      $(option).attr('id', 'chozen_chzn_b_' + this.options_index);
      return this.options_index += 1;
    }
  };
  SelectParser.prototype.push_tree = function(name) {
    var a, b, c;
    a = name[0];
    b = name[1];
    c = name[2];
    if (a === void 0 || b === void 0) {
      return;
    }
    a = a.toLowerCase();
    b = b.toLowerCase();
    if (this.tree[a] === void 0) {
      this.tree[a] = {};
    }
    if (this.tree[a][b] === void 0) {
      this.tree[a][b] = {
        data: []
      };
    }
    this.tree[a][b].data.push(this.parsed[this.parsed.length - 1]);
    if (c === void 0) {
      return;
    }
    c = c.toLowerCase();
    if (this.tree[a][b][c] === void 0) {
      this.tree[a][b][c] = {
        data: []
      };
    }
    return this.tree[a][b][c].data.push(this.parsed[this.parsed.length - 1]);
  };
  return SelectParser;
})();
SelectParser.select_to_array = function(select) {
  var child, parser, _i, _len, _ref;
  parser = new SelectParser();
  _ref = select.childNodes;
  for (_i = 0, _len = _ref.length; _i < _len; _i++) {
    child = _ref[_i];
    parser.add_node(child);
  }
  root.SelectTree = parser.tree;
  return parser.parsed;
};
this.SelectParser = SelectParser;
/*
Chosen source: generate output using 'cake build'
Copyright (c) 2011 by Harvest
*/
root = this;
$ = jQuery;
$.fn.extend({
  chosen: function(options) {
    if ($.browser.msie && ($.browser.version === "6.0" || $.browser.version === "7.0")) {
      return this;
    }
    return $(this).each(function(input_field) {
      if (!($(this)).hasClass("chzn-done")) {
        return new Chosen(this, options);
      }
    });
  }
});
Chosen = (function() {
  __extends(Chosen, AbstractChosen);
  function Chosen() {
    Chosen.__super__.constructor.apply(this, arguments);
  }
  Chosen.prototype.setup = function() {
    this.form_field_jq = $(this.form_field);
    return this.is_rtl = this.form_field_jq.hasClass("chzn-rtl");
  };
  Chosen.prototype.finish_setup = function() {
    return this.form_field_jq.addClass("chzn-done");
  };
  Chosen.prototype.set_up_html = function() {
    var container_div, dd_top, dd_width, sf_width;
    this.container_id = this.form_field.id.length ? this.form_field.id.replace(/(:|\.)/g, '_') : this.generate_field_id();
    this.container_id += "_chzn";
    this.default_text = this.form_field_jq.data('placeholder') ? this.form_field_jq.data('placeholder') : this.default_text_default;
    container_div = $("<div />", {
      id: this.container_id,
      "class": "chzn-container" + (this.is_rtl ? ' chzn-rtl' : ''),
      style: 'width: 90%;'
    });
    if (this.is_multiple) {
      container_div.html('<ul class="chzn-choices"><li class="search-field"><input type="text" value="' + this.default_text + '" class="default" autocomplete="off" /></li></ul><div class="chzn-drop" style="left:-9000px;"><ul class="chzn-results"></ul></div>');
    } else {
      container_div.html('<a href="javascript:void(0)" class="chzn-single"><span>' + this.default_text + '</span><div><b></b></div></a><div class="chzn-drop" style="left:-9000px;"><div class="chzn-search"><input type="text" autocomplete="off" /></div><ul class="chzn-results"></ul></div>');
    }
    this.form_field_jq.hide().after(container_div);
    this.container = $('#' + this.container_id);
    this.container.addClass("chzn-container-" + (this.is_multiple ? "multi" : "single"));
    if (!this.is_multiple && this.form_field.options.length <= this.disable_search_threshold) {
      this.container.addClass("chzn-container-single-nosearch");
    }
    this.dropdown = this.container.find('div.chzn-drop').first();
    this.dropdown.css({
      "width": "100%",
      "top": "-1000px"
    });
    this.search_field = this.container.find('input').first();
    this.search_results = this.container.find('ul.chzn-results').first();
    this.search_field_scale();
    this.search_no_results = this.container.find('li.no-results').first();
    if (this.is_multiple) {
      this.search_choices = this.container.find('ul.chzn-choices').first();
      this.search_container = this.container.find('li.search-field').first();
    } else {
      this.search_container = this.container.find('div.chzn-search').first();
      this.selected_item = this.container.find('.chzn-single').first();
      this.search_field.css({
        "width": "100%"
      });
    }
    this.results_build();
    return this.set_tab_index();
  };
  Chosen.prototype.register_observers = function() {
    this.container.mousedown(__bind(function(evt) {
      return this.container_mousedown(evt);
    }, this));
    this.container.mouseup(__bind(function(evt) {
      return this.container_mouseup(evt);
    }, this));
    this.container.mouseenter(__bind(function(evt) {
      return this.mouse_enter(evt);
    }, this));
    this.container.mouseleave(__bind(function(evt) {
      return this.mouse_leave(evt);
    }, this));
    this.search_results.mouseup(__bind(function(evt) {
      return this.search_results_mouseup(evt);
    }, this));
    this.search_results.mouseover(__bind(function(evt) {
      return this.search_results_mouseover(evt);
    }, this));
    this.search_results.mouseout(__bind(function(evt) {
      return this.search_results_mouseout(evt);
    }, this));
    this.form_field_jq.bind("liszt:updated", __bind(function(evt) {
      return this.results_update_field(evt);
    }, this));
    this.form_field_jq.bind("liszt:added", __bind(function(evt) {
      return this.results_add(arguments);
    }, this));
    this.form_field_jq.bind("liszt:selected", __bind(function(evt) {
      return this.result_select(evt);
    }, this));
    this.search_field.blur(__bind(function(evt) {
      return this.input_blur(evt);
    }, this));
    this.search_field.keyup(__bind(function(evt) {
      return this.keyup_checker(evt);
    }, this));
    this.search_field.keydown(__bind(function(evt) {
      return this.keydown_checker(evt);
    }, this));
    if (this.is_multiple) {
      this.search_choices.click(__bind(function(evt) {
        return this.choices_click(evt);
      }, this));
      return this.search_field.focus(__bind(function(evt) {
        return this.input_focus(evt);
      }, this));
    }
  };
  Chosen.prototype.search_field_disabled = function() {
    this.is_disabled = this.form_field_jq.attr('disabled');
    if (this.is_disabled) {
      this.container.addClass('chzn-disabled');
      this.search_field.attr('disabled', true);
      if (!this.is_multiple) {
        this.selected_item.unbind("focus", this.activate_action);
      }
      return this.close_field();
    } else {
      this.container.removeClass('chzn-disabled');
      this.search_field.attr('disabled', false);
      if (!this.is_multiple) {
        return this.selected_item.bind("focus", this.activate_action);
      }
    }
  };
  Chosen.prototype.container_mousedown = function(evt) {
	if (evt != undefined && evt.target != undefined && evt.target.nodeName == "SPAN") {
		return;
	}
    var target_closelink;
    if (!this.is_disabled) {
      target_closelink = evt != null ? ($(evt.target)).hasClass("search-choice-close") : false;
      if (evt && evt.type === "mousedown") {
        evt.stopPropagation();
      }
      if (!this.pending_destroy_click && !target_closelink) {
        if (!this.active_field) {
          this.results_show();
        } else if (!this.is_multiple && evt && ($(evt.target) === this.selected_item || $(evt.target).parents("a.chzn-single").length)) {
          evt.preventDefault();
          this.results_toggle();
        }
        return this.activate_field();
      } else {
        return this.pending_destroy_click = false;
      }
    }
  };
  Chosen.prototype.container_mouseup = function(evt) {
    if (evt.target.nodeName === "ABBR") {
      return this.results_reset(evt);
    }
  };
  Chosen.prototype.blur_test = function(evt) {
    if (
		!this.active_field &&
		this.container.hasClass("chzn-container-active") &&
		document.body.status != 'blurred'
	) {
		this.close_field();
    }
  };
  Chosen.prototype.close_field = function() {
    if (!this.is_multiple) {
      this.selected_item.attr("tabindex", this.search_field.attr("tabindex"));
      this.search_field.attr("tabindex", -1);
    }
    if (this.search_field.val() !== this.default_text) {
      this.form_field_jq.trigger('close', $('<div/>').text($.trim(this.search_field.val())).html());
    }
    this.active_field = false;
    this.results_hide();
    this.container.removeClass("chzn-container-active");
    this.winnow_results_clear();
    this.clear_backstroke();
    this.show_search_field_default();
    this.search_field_scale();
  };
  Chosen.prototype.activate_field = function() {
    if (!this.is_multiple && !this.active_field) {
      this.search_field.attr("tabindex", this.selected_item.attr("tabindex"));
      this.selected_item.attr("tabindex", -1);
    }
    this.container.addClass("chzn-container-active");
    this.active_field = true;
    this.search_field.val(this.search_field.val());
    return this.search_field.focus();
  };
  Chosen.prototype.results_build = function() {
    var content, data, startTime, _i, _len, _ref;
    startTime = new Date();
    this.parsing = true;
    this.results_data = root.SelectParser.select_to_array(this.form_field);
    if (this.is_multiple && this.choices > 0) {
      this.search_choices.find("li.search-choice").remove();
      this.choices = 0;
    } else if (!this.is_multiple) {
      this.selected_item.find("span").text(this.default_text);
    }
    content = '';
    _ref = this.results_data;
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      data = _ref[_i];
      if (data.group) {
        content += this.result_add_group(data);
      } else if (!data.empty) {
        content += this.result_add_option(data);
        if (data.selected && this.is_multiple) {
          this.choice_build(data);
        } else if (data.selected && !this.is_multiple) {
          this.selected_item.find("span").text(data.text);
          if (this.allow_single_deselect) {
            this.selected_item.find("span").first().after("<abbr class=\"search-choice-close\"></abbr>");
          }
        }
      }
    }
    this.search_field_disabled();
    this.show_search_field_default();
    this.search_field_scale();
    this.search_results.html(content);
    return this.parsing = false;
  };
  Chosen.prototype.results_add = function(options) {
    var data;
	me = this;
	var done = false;

	$.each(options, function(key, option) {
	  if (option.text == undefined) {
		  return;
	  }

      data = {};
      data.array_index = me.results_data.length;
      data.options_index = me.results_data.length;
      data.value = option.text;
      data.text = option.text;
      data.html = option.text + option.html;
      data.selected = true;
      data.disabled = false;
      data.group_array_index = 0;
      data.classes = "";
      data.style = "color: #" + option.color + ";";
      me.choice_build(data);

      done = true;
    });
    if (done) {
		$(".active-result").removeClass("active-result");
		this.winnow_results_clear();
		this.search_field_scale();
	}
  };
  Chosen.prototype.result_add_group = function(group) {
    if (!group.disabled) {
      group.dom_id = this.container_id + "_g_" + group.array_index;
      return '<li id="' + group.dom_id + '" class="group-result">' + $("<div />").text(group.label).html() + '</li>';
    } else {
      return "";
    }
  };
  Chosen.prototype.result_do_highlight = function(el) {
    var high_bottom, high_top, maxHeight, visible_bottom, visible_top;
    if (el.length) {
      this.result_clear_highlight();
      this.result_highlight = el;
      this.result_highlight.addClass("highlighted");
      maxHeight = parseInt(this.search_results.css("maxHeight"), 10);
      visible_top = this.search_results.scrollTop();
      visible_bottom = maxHeight + visible_top;
      high_top = this.result_highlight.position().top + this.search_results.scrollTop();
      high_bottom = high_top + this.result_highlight.outerHeight();
      if (high_bottom >= visible_bottom) {
        return this.search_results.scrollTop((high_bottom - maxHeight) > 0 ? high_bottom - maxHeight : 0);
      } else if (high_top < visible_top) {
        return this.search_results.scrollTop(high_top);
      }
    }
  };
  Chosen.prototype.result_clear_highlight = function() {
    if (this.result_highlight) {
      this.result_highlight.removeClass("highlighted");
    }
    return this.result_highlight = null;
  };
  Chosen.prototype.results_show = function() {
    var dd_top;
    if (!this.is_multiple) {
      this.selected_item.addClass("chzn-single-with-drop");
      if (this.result_single_selected) {
        this.result_do_highlight(this.result_single_selected);
      }
    }
    dd_top = this.is_multiple ? this.container.height() : this.container.height() - 1;
    this.dropdown.css({
      "top": dd_top + "px",
      "left": 0
    });
    this.results_showing = true;
    this.search_field.focus();
    this.search_field.val(this.search_field.val());
    return this.winnow_results();
  };
  Chosen.prototype.results_hide = function() {
    this.result_clear_highlight();
    this.dropdown.css({
      "left": "-9000px"
    });
    return this.results_showing = false;
  };
  Chosen.prototype.set_tab_index = function(el) {
    var ti;
    if (this.form_field_jq.attr("tabindex")) {
      ti = this.form_field_jq.attr("tabindex");
      this.form_field_jq.attr("tabindex", -1);
      if (this.is_multiple) {
        return this.search_field.attr("tabindex", ti);
      } else {
        this.selected_item.attr("tabindex", ti);
        return this.search_field.attr("tabindex", -1);
      }
    }
  };
  Chosen.prototype.show_search_field_default = function() {
    if (this.is_multiple && this.choices < 1 && !this.active_field) {
      this.search_field.val(this.default_text);
      return this.search_field.addClass("default");
    } else {
      this.search_field.val("");
      return this.search_field.removeClass("default");
    }
  };
  Chosen.prototype.search_results_mouseup = function(evt) {
    var target;
    target = $(evt.target).hasClass("active-result") ? $(evt.target) : $(evt.target).parents(".active-result").first();
    if (target.length) {
      this.result_highlight = target;
      return this.result_select(evt);
    }
  };
  Chosen.prototype.search_results_mouseover = function(evt) {
    var target;
    target = $(evt.target).hasClass("active-result") ? $(evt.target) : $(evt.target).parents(".active-result").first();
    if (target) {
      return this.result_do_highlight(target);
    }
  };
  Chosen.prototype.search_results_mouseout = function(evt) {
    if ($(evt.target).hasClass("active-result" || $(evt.target).parents('.active-result').first())) {
      return this.result_clear_highlight();
    }
  };
  Chosen.prototype.choices_click = function(evt) {
    evt.preventDefault();
    if (this.active_field && !($(evt.target).hasClass("search-choice" || $(evt.target).parents('.search-choice').first)) && !this.results_showing) {
      return this.results_show();
    }
  };
  Chosen.prototype.choice_build = function(item) {
    var choice_id, link, style;
    choice_id = this.container_id + "_c_" + item.array_index;
    this.choices += 1;
    if (item.style) {
      style = ' style="' + item.style + '"';
    } else {
      style = '';
    }
    this.search_container.before('<li class="search-choice" id="' + choice_id + '"' + style + '><span>' + item.html + '</span><a href="javascript:void(0)" class="search-choice-close" rel="' + item.array_index + '"></a></li>');
    link = $('#' + choice_id).find("a").first();
    return link.click(__bind(function(evt) {
      return this.choice_destroy_link_click(evt);
    }, this));
  };
  Chosen.prototype.choice_destroy_link_click = function(evt) {
    evt.preventDefault();
    if (!this.is_disabled) {
      this.pending_destroy_click = true;
      return this.choice_destroy($(evt.target));
    } else {
      return evt.stopPropagation;
    }
  };
  Chosen.prototype.choice_destroy = function(link) {
    this.choices -= 1;
    this.show_search_field_default();
    if (this.is_multiple && this.choices > 0 && this.search_field.val().length < 1) {
      this.results_hide();
    }
    this.result_deselect(link.attr("rel"));
    return link.parents('li').first().remove();
  };
  Chosen.prototype.results_reset = function(evt) {
    this.form_field.options[0].selected = true;
    this.selected_item.find("span").text(this.default_text);
    this.show_search_field_default();
    $(evt.target).remove();
    this.form_field_jq.trigger("change");
    if (this.active_field) {
      return this.results_hide();
    }
  };
  Chosen.prototype.result_select = function(evt) {
    var high, high_id, item, position;
    if (this.result_highlight) {
      high = this.result_highlight;
      high_id = high.attr("id");
      this.result_clear_highlight();
      if (this.is_multiple) {
        this.result_deactivate(high);
      } else {
        this.search_results.find(".result-selected").removeClass("result-selected");
        this.result_single_selected = high;
      }
      high.addClass("result-selected");
      position = high_id.substr(high_id.lastIndexOf("_") + 1);
      item = this.results_data[position];
      item.selected = true;
      this.form_field.options[item.options_index].selected = true;
      if (this.is_multiple) {
        this.choice_build(item);
      } else {
        this.selected_item.find("span").first().text(item.text);
        if (this.allow_single_deselect) {
          this.selected_item.find("span").first().after("<abbr class=\"search-choice-close\"></abbr>");
        }
      }
      if (!(evt.metaKey && this.is_multiple)) {
        this.results_hide();
      }
      this.search_field.val("");
      this.form_field_jq.trigger("change");
      return this.search_field_scale();
    }
  };
  Chosen.prototype.result_activate = function(el) {
    return el.addClass("active-result");
  };
  Chosen.prototype.result_deactivate = function(el) {
    return el.removeClass("active-result");
  };
  Chosen.prototype.result_deselect = function(pos) {
    var result, result_data;
    result_data = this.results_data[pos];

    if (result_data != undefined) {
		result_data.selected = false;
		this.form_field.options[result_data.options_index].selected = false;
		result = $("#" + this.container_id + "_o_" + pos);
		result.removeClass("result-selected").addClass("active-result").show();
		this.result_clear_highlight();
	} else {
		result = $("#" + this.container_id + "_o_" + pos);
		result.remove();
	}

    this.winnow_results();
    this.form_field_jq.trigger("change");
    return this.search_field_scale();
  };
  Chosen.prototype.winnow_results = function() {
    var a, b, c, found, no_search_html, option, regex, result_id, results, searchText, search_data, startTime, startpos, text, zregex, _i, _len;
    startTime = new Date();
    this.no_results_clear();
    results = 0;
    $(".active-result").removeClass("active-result");
    searchText = this.search_field.val() === this.default_text ? "" : $('<div/>').text($.trim(this.search_field.val())).html();
    if (searchText.length < 2) {
      no_search_html = $('<li class="no-results">Подсказки начинаются от 2 символов<span class="hidden">' + searchText + '</span></li>');
      this.search_results.append(no_search_html);
      return "";
    }
    if (this.result_highlight != undefined) {
		var highlighted = this.result_highlight.text();
		if (highlighted.toLowerCase().indexOf(searchText.toLowerCase()) == -1) {
			this.result_clear_highlight();
		}
	}

    a = searchText[0].toLowerCase();
    b = searchText[1].toLowerCase();
    c = searchText[2];
    if (c !== void 0) {
      c = c.toLowerCase();
    }
    if (root.SelectTree[a] === void 0 || root.SelectTree[a][b] === void 0) {
      return this.no_results(searchText);
    }
    if (c !== void 0 && root.SelectTree[a][b][c] === void 0) {
      return this.no_results(searchText);
    }
    if (c !== void 0) {
      search_data = root.SelectTree[a][b][c].data;
    } else {
      search_data = root.SelectTree[a][b].data;
    }
    regex = new RegExp('^' + searchText.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"), 'i');
    zregex = new RegExp(searchText.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"), 'i');
    for (_i = 0, _len = search_data.length; _i < _len; _i++) {
      option = search_data[_i];
      if (!option.disabled && !option.empty) {
        if (option.group) {
          $('#' + option.dom_id).hide();
        } else if (!(this.is_multiple && option.selected)) {
          found = false;
          result_id = option.dom_id;
          if (regex.test(option.html)) {
            found = true;
            results += 1;
          }
          if (found) {
            if (searchText.length) {
              startpos = option.html.search(zregex);
              text = option.html.substr(0, startpos + searchText.length) + '</em>' + option.html.substr(startpos + searchText.length);
              text = text.substr(0, startpos) + '<em>' + text.substr(startpos);
            } else {
              text = option.html;
            }
            if ($("#" + result_id).html !== text) {
              $("#" + result_id).html(text);
            }
            this.result_activate($("#" + result_id));
            if (option.group_array_index != null) {
              $("#" + this.results_data[option.group_array_index].dom_id).show();
            }
          } else {
            if (this.result_highlight && result_id === this.result_highlight.attr('id')) {
              this.result_clear_highlight();
            }
            this.result_deactivate($("#" + result_id));
          }
        }
      }
    }
    if (results < 1 && searchText.length) {
      return this.no_results(searchText);
    } else {
      return this.winnow_results_set_highlight();
    }
  };
  Chosen.prototype.winnow_results_clear = function() {
    var li, lis, _i, _len, _results;
    this.search_field.val("");
    lis = this.search_results.find("li");
    $("li.no-results").remove();
   _results = [];
    return _results;
  };
  Chosen.prototype.winnow_results_set_highlight = function() {
    var do_high, selected_results;
    if (!this.result_highlight) {
      selected_results = !this.is_multiple ? this.search_results.find(".result-selected.active-result") : [];
      do_high = selected_results.length ? selected_results.first() : this.search_results.find(".active-result").first();
      if (do_high != null) {
        return this.result_do_highlight(do_high);
      }
    }
  };
  Chosen.prototype.no_results = function(terms) {
    var no_results_html;
    no_results_html = $('<li class="no-results">' + this.results_none_found + ' "<span></span>"</li>');
    no_results_html.find("span").first().html(terms);
    return this.search_results.append(no_results_html);
  };
  Chosen.prototype.no_results_clear = function() {
    return this.search_results.find(".no-results").remove();
  };
  Chosen.prototype.keydown_arrow = function() {
    var first_active, next_sib;
    if (!this.result_highlight) {
      first_active = this.search_results.find("li.active-result").first();
      if (first_active) {
        this.result_do_highlight($(first_active));
      }
    } else if (this.results_showing) {
      next_sib = this.result_highlight.nextAll("li.active-result").first();
      if (next_sib) {
        this.result_do_highlight(next_sib);
      }
    }
    if (!this.results_showing) {
      return this.results_show();
    }
  };
  Chosen.prototype.keyup_arrow = function() {
    var prev_sibs;
    if (!this.results_showing && !this.is_multiple) {
      return this.results_show();
    } else if (this.result_highlight) {
      prev_sibs = this.result_highlight.prevAll("li.active-result");
      if (prev_sibs.length) {
        return this.result_do_highlight(prev_sibs.first());
      } else {
        if (this.choices > 0) {
          this.results_hide();
        }
        return this.result_clear_highlight();
      }
    }
  };
  Chosen.prototype.keydown_backstroke = function() {
    if (this.pending_backstroke) {
      this.choice_destroy(this.pending_backstroke.find("a").first());
      return this.clear_backstroke();
    } else {
      this.pending_backstroke = this.search_container.siblings("li.search-choice").last();
      return this.pending_backstroke.addClass("search-choice-focus");
    }
  };
  Chosen.prototype.clear_backstroke = function() {
    if (this.pending_backstroke) {
      this.pending_backstroke.removeClass("search-choice-focus");
    }
    return this.pending_backstroke = null;
  };
  Chosen.prototype.keydown_checker = function(evt) {
    var stroke, _ref;
    stroke = (_ref = evt.which) != null ? _ref : evt.keyCode;
    this.search_field_scale();
    if (stroke !== 8 && this.pending_backstroke) {
      this.clear_backstroke();
    }
    switch (stroke) {
      case 8:
        this.backstroke_length = this.search_field.val().length;
        break;
      case 9:
        this.mouse_on_container = false;
        break;
      case 13:
        evt.preventDefault();
        break;
      case 38:
        evt.preventDefault();
        this.keyup_arrow();
        break;
      case 40:
        this.keydown_arrow();
        break;
    }
  };
  Chosen.prototype.search_field_scale = function() {
    var dd_top, div, h, style, style_block, styles, w, _i, _len;
    if (this.is_multiple) {
      h = 0;
      w = 0;
      style_block = "position:absolute; left: -1000px; top: -1000px; display:none;";
      styles = ['font-size', 'font-style', 'font-weight', 'font-family', 'line-height', 'text-transform', 'letter-spacing'];
      div = $('<div />', {
        'style': style_block
      });
      div.text(this.search_field.val());
      $('body').append(div);
      div.remove();
      dd_top = this.container.height();
      return this.dropdown.css({
        "top": dd_top + "px"
      });
    }
  };
  Chosen.prototype.generate_random_id = function() {
    var string;
    string = "sel" + this.generate_random_char() + this.generate_random_char() + this.generate_random_char();
    while ($("#" + string).length > 0) {
      string += this.generate_random_char();
    }
    return string;
  };
  return Chosen;
})();
get_side_border_padding = function(elmt) {
  var side_border_padding;
  return side_border_padding = elmt.outerWidth() - elmt.width();
};
root.get_side_border_padding = get_side_border_padding;

/**
 * @license
 * jQuery Tools @VERSION Dateinput - <input type="date" /> for humans
 *
 * NO COPYRIGHTS OR LICENSES. DO WHAT YOU LIKE.
 *
 * http://flowplayer.org/tools/form/dateinput/
 *
 * Since: Mar 2010
 * Date: @DATE
 */
(function($) {

	/* TODO:
		 preserve today highlighted
	*/

	$.tools = $.tools || {version: '@VERSION'};

	var instances = [],
		 tool,

		 // h=72, j=74, k=75, l=76, down=40, left=37, up=38, right=39
		 KEYS = [75, 76, 38, 39, 74, 72, 40, 37],
		 LABELS = {};

	tool = $.tools.dateinput = {

		conf: {
			format: 'mm/dd/yy',
			selectors: false,
			yearRange: [-5, 5],
			lang: 'en',
			offset: [0, 0],
			speed: 0,
			firstDay: 0, // The first day of the week, Sun = 0, Mon = 1, ...
			min: undefined,
			max: undefined,
			trigger: false,

			css: {

				prefix: 'cal',
				input: 'date',

				// ids
				root: 0,
				head: 0,
				title: 0,
				prev: 0,
				next: 0,
				month: 0,
				year: 0,
				days: 0,

				body: 0,
				weeks: 0,
				today: 0,
				current: 0,

				// classnames
				week: 0,
				off: 0,
				sunday: 0,
				focus: 0,
				disabled: 0,
				trigger: 0
			}
		},

		localize: function(language, labels) {
			$.each(labels, function(key, val) {
				labels[key] = val.split(",");
			});
			LABELS[language] = labels;
		}

	};

	tool.localize("en", {
		months: 		 'January,February,March,April,May,June,July,August,September,October,November,December',
		shortMonths: 'Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec',
		days: 		 'Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
		shortDays: 	 'Sun,Mon,Tue,Wed,Thu,Fri,Sat'
	});


//{{{ private functions


	// @return amount of days in certain month
	function dayAm(year, month) {
		return 32 - new Date(year, month, 32).getDate();
	}

	function zeropad(val, len) {
		val = '' + val;
		len = len || 2;
		while (val.length < len) { val = "0" + val; }
		return val;
	}

	// thanks: http://stevenlevithan.com/assets/misc/date.format.js
	var Re = /d{1,4}|m{1,4}|yy(?:yy)?|"[^"]*"|'[^']*'/g, tmpTag = $("<a/>");

	function format(date, fmt, lang) {

	  var d = date.getDate(),
			D = date.getDay(),
			m = date.getMonth(),
			y = date.getFullYear(),

			flags = {
				d:    d,
				dd:   zeropad(d),
				ddd:  LABELS[lang].shortDays[D],
				dddd: LABELS[lang].days[D],
				m:    m + 1,
				mm:   zeropad(m + 1),
				mmm:  LABELS[lang].shortMonths[m],
				mmmm: LABELS[lang].months[m],
				yy:   String(y).slice(2),
				yyyy: y
			};

		var ret = fmt.replace(Re, function ($0) {
			return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
		});

		// a small trick to handle special characters
		return tmpTag.html(ret).html();

	}

	function integer(val) {
		return parseInt(val, 10);
	}

	function isSameDay(d1, d2)  {
		return d1.getFullYear() === d2.getFullYear() &&
			d1.getMonth() == d2.getMonth() &&
			d1.getDate() == d2.getDate();
	}

	function parseDate(val) {

		if (!val) { return; }
		if (val.constructor == Date) { return val; }

		if (typeof val == 'string') {

			// rfc3339?
			var els = val.split("-");
			if (els.length == 3) {
				return new Date(integer(els[0]), integer(els[1]) -1, integer(els[2]));
			}

			// invalid offset
			if (!/^-?\d+$/.test(val)) { return; }

			// convert to integer
			val = integer(val);
		}

		var date = new Date();
		date.setDate(date.getDate() + val);
		return date;
	}

//}}}


	function Dateinput(input, conf)  {

		// variables
		var self = this,
			 now = new Date(),
			 css = conf.css,
			 labels = LABELS[conf.lang],
			 root = $("#" + css.root),
			 title = root.find("#" + css.title),
			 trigger,
			 pm, nm,
			 currYear, currMonth, currDay,
			 value = input.attr("data-value") || conf.value || input.val(),
			 min = input.attr("min") || conf.min,
			 max = input.attr("max") || conf.max,
			 opened;

		// zero min is not undefined
		if (min === 0) { min = "0"; }

		// use sane values for value, min & max
		value = parseDate(value) || now;
		min   = parseDate(min || conf.yearRange[0] * 365);
		max   = parseDate(max || conf.yearRange[1] * 365);


		// check that language exists
		if (!labels) { throw "Dateinput: invalid language: " + conf.lang; }

		// Replace built-in date input: NOTE: input.attr("type", "text") throws exception by the browser
		if (input.attr("type") == 'date') {
			var tmp = $("<input/>");

			$.each("class,disabled,id,maxlength,name,readonly,required,size,style,tabindex,title,value".split(","), function(i, attr)  {
				tmp.attr(attr, input.attr(attr));
			});
			input.replaceWith(tmp);
			input = tmp;
		}
		input.addClass(css.input);

		var fire = input.add(self);

		// construct layout
		if (!root.length) {

			// root
			root = $('<div><div><a/><div/><a/></div><div><div/><div/></div></div>')
				.hide().css({position: 'absolute'}).attr("id", css.root);

			// elements
			root.children()
				.eq(0).attr("id", css.head).end()
				.eq(1).attr("id", css.body).children()
					.eq(0).attr("id", css.days).end()
					.eq(1).attr("id", css.weeks).end().end().end()
				.find("a").eq(0).attr("id", css.prev).end().eq(1).attr("id", css.next);

			// title
			title = root.find("#" + css.head).find("div").attr("id", css.title);

			// year & month selectors
			if (conf.selectors) {
				var monthSelector = $("<select/>").attr("id", css.month),
					 yearSelector = $("<select/>").attr("id", css.year);
				title.html(monthSelector.add(yearSelector));
			}

			// day titles
			var days = root.find("#" + css.days);

			// days of the week
			for (var d = 0; d < 7; d++) {
				days.append($("<span/>").text(labels.shortDays[(d + conf.firstDay) % 7]));
			}

			$("body").append(root);
		}


		// trigger icon
		if (conf.trigger) {
			trigger = $("<a/>").attr("href", "#").addClass(css.trigger).click(function(e)  {
				self.show();
				return e.preventDefault();
			}).insertAfter(input);
		}


		// layout elements
		var weeks = root.find("#" + css.weeks);
		yearSelector = root.find("#" + css.year);
		monthSelector = root.find("#" + css.month);


//{{{ pick

		function select(date, conf, e) {

			// current value
			value 	 = date;
			currYear  = date.getFullYear();
			currMonth = date.getMonth();
			currDay	 = date.getDate();


			// change
			e = e || $.Event("api");
			e.type = "change";

			fire.trigger(e, [date]);
			if (e.isDefaultPrevented()) { return; }

			// formatting
			input.val(format(date, conf.format, conf.lang));

			// store value into input
			input.data("date", date);

			self.hide(e);
		}
//}}}


//{{{ onShow

		function onShow(ev) {

			ev.type = "onShow";
			fire.trigger(ev);

			$(document).bind("keydown.d", function(e) {

				if (e.ctrlKey) { return true; }
				var key = e.keyCode;

				// backspace clears the value
				if (key == 8) {
					input.val("");
					return self.hide(e);
				}

				// esc key
				if (key == 27) { return self.hide(e); }

				if ($(KEYS).index(key) >= 0) {

					if (!opened) {
						self.show(e);
						return e.preventDefault();
					}

					var days = $("#" + css.weeks + " a"),
						 el = $("." + css.focus),
						 index = days.index(el);

					el.removeClass(css.focus);

					if (key == 74 || key == 40) { index += 7; }
					else if (key == 75 || key == 38) { index -= 7; }
					else if (key == 76 || key == 39) { index += 1; }
					else if (key == 72 || key == 37) { index -= 1; }


					if (index > 41) {
						 self.addMonth();
						 el = $("#" + css.weeks + " a:eq(" + (index-42) + ")");
					} else if (index < 0) {
						 self.addMonth(-1);
						 el = $("#" + css.weeks + " a:eq(" + (index+42) + ")");
					} else {
						 el = days.eq(index);
					}

					el.addClass(css.focus);
					return e.preventDefault();

				}

				// pageUp / pageDown
				if (key == 34) { return self.addMonth(); }
				if (key == 33) { return self.addMonth(-1); }

				// home
				if (key == 36) { return self.today(); }

				// enter
				if (key == 13) {
					if (!$(e.target).is("select")) {
						$("." + css.focus).click();
					}
				}

				return $([16, 17, 18, 9]).index(key) >= 0;
			});


			// click outside dateinput
			$(document).bind("click.d", function(e) {
				var el = e.target;

				if (!$(el).parents("#" + css.root).length && el != input[0] && (!trigger || el != trigger[0])) {
					self.hide(e);
				}

			});
		}
//}}}


		$.extend(self, {

//{{{  show

			show: function(e) {

				if (input.attr("readonly") || input.attr("disabled") || opened) { return; }

				// onBeforeShow
				e = e || $.Event();
				e.type = "onBeforeShow";
				fire.trigger(e);
				if (e.isDefaultPrevented()) { return; }

				$.each(instances, function() {
					this.hide();
				});

				opened = true;

				// month selector
				monthSelector.unbind("change").change(function() {
					self.setValue(yearSelector.val(), $(this).val());
				});

				// year selector
				yearSelector.unbind("change").change(function() {
					self.setValue($(this).val(), monthSelector.val());
				});

				// prev / next month
				pm = root.find("#" + css.prev).unbind("click").click(function(e) {
					if (!pm.hasClass(css.disabled)) {
						self.addMonth(-1);
					}
					return false;
				});

				nm = root.find("#" + css.next).unbind("click").click(function(e) {
					if (!nm.hasClass(css.disabled)) {
						self.addMonth();
					}
					return false;
				});

				// set date
				self.setValue(value);

				// show calendar
				var pos = input.offset();

				// iPad position fix
				if (/iPad/i.test(navigator.userAgent)) {
					pos.top -= $(window).scrollTop();
				}

				root.css({
					top: pos.top + input.outerHeight({margins: true}) + conf.offset[0],
					left: pos.left + conf.offset[1]
				});

				if (conf.speed) {
					root.show(conf.speed, function() {
						onShow(e);
					});
				} else {
					root.show();
					onShow(e);
				}

				return self;
			},
//}}}


//{{{  setValue

			setValue: function(year, month, day)  {

				var date = integer(month) >= -1 ? new Date(integer(year), integer(month), integer(day || 1)) :
					year || value;

				if (date < min) { date = min; }
				else if (date > max) { date = max; }

				year = date.getFullYear();
				month = date.getMonth();
				day = date.getDate();


				// roll year & month
				if (month == -1) {
					month = 11;
					year--;
				} else if (month == 12) {
					month = 0;
					year++;
				}

				if (!opened) {
					select(date, conf);
					return self;
				}

				currMonth = month;
				currYear = year;

				// variables
				var tmp = new Date(year, month, 1 - conf.firstDay), begin = tmp.getDay(),
					 days = dayAm(year, month),
					 prevDays = dayAm(year, month - 1),
					 week;

				// selectors
				if (conf.selectors) {

					// month selector
					monthSelector.empty();
					$.each(labels.months, function(i, m) {
						if (min < new Date(year, i + 1, -1) && max > new Date(year, i, 0)) {
							monthSelector.append($("<option/>").html(m).attr("value", i));
						}
					});

					// year selector
					yearSelector.empty();
					var yearNow = now.getFullYear();

					for (var i = yearNow + conf.yearRange[0];  i < yearNow + conf.yearRange[1]; i++) {
						if (min <= new Date(i + 1, -1, 1) && max > new Date(i, 0, 0)) {
							yearSelector.append($("<option/>").text(i));
						}
					}

					monthSelector.val(month);
					yearSelector.val(year);

				// title
				} else {
					title.html(labels.months[month] + " " + year);
				}

				// populate weeks
				weeks.empty();
				pm.add(nm).removeClass(css.disabled);

				// !begin === "sunday"
				for (var j = !begin ? -7 : 0, a, num; j < (!begin ? 35 : 42); j++) {

					a = $("<a/>");

					if (j % 7 === 0) {
						week = $("<div/>").addClass(css.week);
						weeks.append(week);
					}

					if (j < begin)  {
						a.addClass(css.off);
						num = prevDays - begin + j + 1;
						date = new Date(year, month-1, num);

					} else if (j >= begin + days)  {
						a.addClass(css.off);
						num = j - days - begin + 1;
						date = new Date(year, month+1, num);

					} else  {
						num = j - begin + 1;
						date = new Date(year, month, num);

						// current date
						if (isSameDay(value, date)) {
							a.attr("id", css.current).addClass(css.focus);

						// today
						} else if (isSameDay(now, date)) {
							a.attr("id", css.today);
						}
					}

					// disabled
					if (min && date < min) {
						a.add(pm).addClass(css.disabled);
					}

					if (max && date > max) {
						a.add(nm).addClass(css.disabled);
					}

					a.attr("href", "#" + num).text(num).data("date", date);

					week.append(a);
				}

				// date picking
				weeks.find("a").click(function(e) {
					var el = $(this);
					if (!el.hasClass(css.disabled)) {
						$("#" + css.current).removeAttr("id");
						el.attr("id", css.current);
						select(el.data("date"), conf, e);
					}
					return false;
				});

				// sunday
				if (css.sunday) {
					weeks.find(css.week).each(function() {
						var beg = conf.firstDay ? 7 - conf.firstDay : 0;
						$(this).children().slice(beg, beg + 1).addClass(css.sunday);
					});
				}

				return self;
			},
	//}}}

			setMin: function(val, fit) {
				min = parseDate(val);
				if (fit && value < min) { self.setValue(min); }
				return self;
			},

			setMax: function(val, fit) {
				max = parseDate(val);
				if (fit && value > max) { self.setValue(max); }
				return self;
			},

			today: function() {
				return self.setValue(now);
			},

			addDay: function(amount) {
				return this.setValue(currYear, currMonth, currDay + (amount || 1));
			},

			addMonth: function(amount) {
				return this.setValue(currYear, currMonth + (amount || 1), currDay);
			},

			addYear: function(amount) {
				return this.setValue(currYear + (amount || 1), currMonth, currDay);
			},

			hide: function(e) {

				if (opened) {

					// onHide
					e = $.Event();
					e.type = "onHide";
					fire.trigger(e);

					$(document).unbind("click.d").unbind("keydown.d");

					// cancelled ?
					if (e.isDefaultPrevented()) { return; }

					// do the hide
					root.hide();
					opened = false;
				}

				return self;
			},

			getConf: function() {
				return conf;
			},

			getInput: function() {
				return input;
			},

			getCalendar: function() {
				return root;
			},

			getValue: function(dateFormat) {
				return dateFormat ? format(value, dateFormat, conf.lang) : value;
			},

			isOpen: function() {
				return opened;
			}

		});

		// callbacks
		$.each(['onBeforeShow','onShow','change','onHide'], function(i, name) {

			// configuration
			if ($.isFunction(conf[name]))  {
				$(self).bind(name, conf[name]);
			}

			// API methods
			self[name] = function(fn) {
				if (fn) { $(self).bind(name, fn); }
				return self;
			};
		});


		// show dateinput & assign keyboard shortcuts
		input.bind("focus click", self.show).keydown(function(e) {

			var key = e.keyCode;

			// open dateinput with navigation keyw
			if (!opened &&  $(KEYS).index(key) >= 0) {
				self.show(e);
				return e.preventDefault();
			}

			// allow tab
			return e.shiftKey || e.ctrlKey || e.altKey || key == 9 ? true : e.preventDefault();

		});

		// initial value
		if (parseDate(input.val())) {
			select(value, conf);
		}

	}

	$.expr[':'].date = function(el) {
		var type = el.getAttribute("type");
		return type && type == 'date' || !!$(el).data("dateinput");
	};


	$.fn.dateinput = function(conf) {

		// already instantiated
		if (this.data("dateinput")) { return this; }

		// configuration
		conf = $.extend(true, {}, tool.conf, conf);

		// CSS prefix
		$.each(conf.css, function(key, val) {
			if (!val && key != 'prefix') {
				conf.css[key] = (conf.css.prefix || '') + (val || key);
			}
		});

		var els;

		this.each(function() {
			var el = new Dateinput($(this), conf);
			instances.push(el);
			var input = el.getInput().data("dateinput", el);
			els = els ? els.add(input) : input;
		});

		return els ? els : this;
	};


}) (jQuery);

/**
 * WYSIWYG - jQuery plugin 0.97
 * (0.97.2 - From infinity)
 *
 * Copyright (c) 2008-2009 Juan M Martinez, 2010-2011 Akzhan Abdulin and all contributors
 * https://github.com/akzhan/jwysiwyg
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 */

/*jslint browser: true, forin: true */

(function ($) {
	"use strict";
	/* Wysiwyg namespace: private properties and methods */

	var console = window.console ? window.console : {
		log: $.noop,
		error: function (msg) {
			$.error(msg);
		}
	};
	var supportsProp = (('prop' in $.fn) && ('removeProp' in $.fn));  // !(/^[01]\.[0-5](?:\.|$)/.test($.fn.jquery));

	function Wysiwyg() {
		this.controls = {
			bold: {
				groupIndex: 0,
				visible: true,
				tags: ["b", "strong"],
				css: {
					fontWeight: "bold"
				},
				tooltip: "Bold",
				hotkey: {"ctrl": 1, "key": 66}
			},

			copy: {
				groupIndex: 8,
				visible: false,
				tooltip: "Copy"
			},

			createLink: {
				groupIndex: 6,
				visible: true,
				exec: function () {
					var self = this;
					if ($.wysiwyg.controls && $.wysiwyg.controls.link) {
						$.wysiwyg.controls.link.init(this);
					} else if ($.wysiwyg.autoload) {
						$.wysiwyg.autoload.control("wysiwyg.link.js", function () {
							self.controls.createLink.exec.apply(self);
						});
					} else {
						console.error("$.wysiwyg.controls.link not defined. You need to include wysiwyg.link.js file");
					}
				},
				tags: ["a"],
				tooltip: "Create link"
			},

			cut: {
				groupIndex: 8,
				visible: false,
				tooltip: "Cut"
			},

			decreaseFontSize: {
				groupIndex: 9,
				visible: false,
				tags: ["small"],
				tooltip: "Decrease font size",
				exec: function () {
					this.decreaseFontSize();
				}
			},

			h1: {
				groupIndex: 7,
				visible: true,
				className: "h1",
				command: ($.browser.msie || $.browser.safari) ? "FormatBlock" : "heading",
				"arguments": ($.browser.msie || $.browser.safari) ? "<h1>" : "h1",
				tags: ["h1"],
				tooltip: "Header 1"
			},

			h2: {
				groupIndex: 7,
				visible: true,
				className: "h2",
				command: ($.browser.msie || $.browser.safari)	? "FormatBlock" : "heading",
				"arguments": ($.browser.msie || $.browser.safari) ? "<h2>" : "h2",
				tags: ["h2"],
				tooltip: "Header 2"
			},

			h3: {
				groupIndex: 7,
				visible: true,
				className: "h3",
				command: ($.browser.msie || $.browser.safari) ? "FormatBlock" : "heading",
				"arguments": ($.browser.msie || $.browser.safari) ? "<h3>" : "h3",
				tags: ["h3"],
				tooltip: "Header 3"
			},

			highlight: {
				tooltip:     "Highlight",
				className:   "highlight",
				groupIndex:  1,
				visible:     false,
				css: {
					backgroundColor: "rgb(255, 255, 102)"
				},
				exec: function () {
					var command, node, selection, args;

					if ($.browser.msie || $.browser.safari) {
						command = "backcolor";
					} else {
						command = "hilitecolor";
					}

					if ($.browser.msie) {
						node = this.getInternalRange().parentElement();
					} else {
						selection = this.getInternalSelection();
						node = selection.extentNode || selection.focusNode;

						while (node.style === undefined) {
							node = node.parentNode;
							if (node.tagName && node.tagName.toLowerCase() === "body") {
								return;
							}
						}
					}

					if (node.style.backgroundColor === "rgb(255, 255, 102)" ||
							node.style.backgroundColor === "#ffff66") {
						args = "#ffffff";
					} else {
						args = "#ffff66";
					}

					this.editorDoc.execCommand(command, false, args);
				}
			},

			html: {
				groupIndex: 10,
				visible: false,
				exec: function () {
					var elementHeight;

					if (this.options.resizeOptions && $.fn.resizable) {
						elementHeight = this.element.height();
					}

					if (this.viewHTML) {
						this.setContent(this.original.value);

						$(this.original).hide();
						this.editor.show();

						if (this.options.resizeOptions && $.fn.resizable) {
							// if element.height still the same after frame was shown
							if (elementHeight === this.element.height()) {
								this.element.height(elementHeight + this.editor.height());
							}

							this.element.resizable($.extend(true, {
								alsoResize: this.editor
							}, this.options.resizeOptions));
						}

						this.ui.toolbar.find("li").each(function () {
							var li = $(this);

							if (li.hasClass("html")) {
								li.removeClass("active");
							} else {
								li.removeClass('disabled');
							}
						});
					} else {
						this.saveContent();

						$(this.original).css({
							width:	this.element.outerWidth() - 6,
							height: this.element.height() - this.ui.toolbar.height() - 6,
							resize: "none"
						}).show();
						this.editor.hide();

						if (this.options.resizeOptions && $.fn.resizable) {
							// if element.height still the same after frame was hidden
							if (elementHeight === this.element.height()) {
								this.element.height(this.ui.toolbar.height());
							}

							this.element.resizable("destroy");
						}

						this.ui.toolbar.find("li").each(function () {
							var li = $(this);

							if (li.hasClass("html")) {
								li.addClass("active");
							} else {
								if (false === li.hasClass("fullscreen")) {
									li.removeClass("active").addClass('disabled');
								}
							}
						});
					}

					this.viewHTML = !(this.viewHTML);
				},
				tooltip: "View source code"
			},

			increaseFontSize: {
				groupIndex: 9,
				visible: false,
				tags: ["big"],
				tooltip: "Increase font size",
				exec: function () {
					this.increaseFontSize();
				}
			},

			indent: {
				groupIndex: 2,
				visible: true,
				tooltip: "Indent"
			},

			insertHorizontalRule: {
				groupIndex: 6,
				visible: true,
				tags: ["hr"],
				tooltip: "Insert Horizontal Rule"
			},

			insertImage: {
				groupIndex: 6,
				visible: true,
				exec: function () {
					var self = this;

					if ($.wysiwyg.controls && $.wysiwyg.controls.image) {
						$.wysiwyg.controls.image.init(this);
					} else if ($.wysiwyg.autoload) {
						$.wysiwyg.autoload.control("wysiwyg.image.js", function () {
							self.controls.insertImage.exec.apply(self);
						});
					} else {
						console.error("$.wysiwyg.controls.image not defined. You need to include wysiwyg.image.js file");
					}
				},
				tags: ["img"],
				tooltip: "Insert image"
			},

			insertOrderedList: {
				groupIndex: 5,
				visible: true,
				tags: ["ol"],
				tooltip: "Insert Ordered List"
			},

			insertTable: {
				groupIndex: 6,
				visible: true,
				exec: function () {
					var self = this;

					if ($.wysiwyg.controls && $.wysiwyg.controls.table) {
						$.wysiwyg.controls.table(this);
					} else if ($.wysiwyg.autoload) {
						$.wysiwyg.autoload.control("wysiwyg.table.js", function () {
							self.controls.insertTable.exec.apply(self);
						});
					} else {
						console.error("$.wysiwyg.controls.table not defined. You need to include wysiwyg.table.js file");
					}
				},
				tags: ["table"],
				tooltip: "Insert table"
			},

			insertUnorderedList: {
				groupIndex: 5,
				visible: true,
				tags: ["ul"],
				tooltip: "Insert Unordered List"
			},

			italic: {
				groupIndex: 0,
				visible: true,
				tags: ["i", "em"],
				css: {
					fontStyle: "italic"
				},
				tooltip: "Italic",
				hotkey: {"ctrl": 1, "key": 73}
			},

			justifyCenter: {
				groupIndex: 1,
				visible: true,
				tags: ["center"],
				css: {
					textAlign: "center"
				},
				tooltip: "Justify Center"
			},

			justifyFull: {
				groupIndex: 1,
				visible: true,
				css: {
					textAlign: "justify"
				},
				tooltip: "Justify Full"
			},

			justifyLeft: {
				visible: true,
				groupIndex: 1,
				css: {
					textAlign: "left"
				},
				tooltip: "Justify Left"
			},

			justifyRight: {
				groupIndex: 1,
				visible: true,
				css: {
					textAlign: "right"
				},
				tooltip: "Justify Right"
			},

			ltr: {
				groupIndex: 10,
				visible: false,
				exec: function () {
					var p = this.dom.getElement("p");

					if (!p) {
						return false;
					}

					$(p).attr("dir", "ltr");
					return true;
				},
				tooltip : "Left to Right"
			},

			outdent: {
				groupIndex: 2,
				visible: true,
				tooltip: "Outdent"
			},

			paragraph: {
				groupIndex: 7,
				visible: false,
				className: "paragraph",
				command: "FormatBlock",
				"arguments": ($.browser.msie || $.browser.safari) ? "<p>" : "p",
				tags: ["p"],
				tooltip: "Paragraph"
			},

			paste: {
				groupIndex: 8,
				visible: false,
				tooltip: "Paste"
			},

			redo: {
				groupIndex: 4,
				visible: true,
				tooltip: "Redo"
			},

			removeFormat: {
				groupIndex: 10,
				visible: true,
				exec: function () {
					this.removeFormat();
				},
				tooltip: "Remove formatting"
			},

			rtl: {
				groupIndex: 10,
				visible: false,
				exec: function () {
					var p = this.dom.getElement("p");

					if (!p) {
						return false;
					}

					$(p).attr("dir", "rtl");
					return true;
				},
				tooltip : "Right to Left"
			},

			strikeThrough: {
				groupIndex: 0,
				visible: true,
				tags: ["s", "strike"],
				css: {
					textDecoration: "line-through"
				},
				tooltip: "Strike-through"
			},

			subscript: {
				groupIndex: 3,
				visible: true,
				tags: ["sub"],
				tooltip: "Subscript"
			},

			superscript: {
				groupIndex: 3,
				visible: true,
				tags: ["sup"],
				tooltip: "Superscript"
			},

			underline: {
				groupIndex: 0,
				visible: true,
				tags: ["u"],
				css: {
					textDecoration: "underline"
				},
				tooltip: "Underline",
				hotkey: {"ctrl": 1, "key": 85}
			},

			undo: {
				groupIndex: 4,
				visible: true,
				tooltip: "Undo"
			},

			code: {
				visible : true,
				groupIndex: 6,
				tooltip: "Code snippet",
				exec: function () {
					var range	= this.getInternalRange(),
						common	= $(range.commonAncestorContainer),
						$nodeName = range.commonAncestorContainer.nodeName.toLowerCase();
					if (common.parent("code").length) {
						common.unwrap();
					} else {
						if ($nodeName !== "body") {
							common.wrap("<code/>");
						}
					}
				}
			}
		};

		this.defaults = {
			html: '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body style="margin: 3px;">INITIAL_CONTENT</body></html>',
			debug: false,
			controls: {},
			css: {},
			events: {},
			autoGrow: false,
			autoSave: true,
			brIE: true,					// http://code.google.com/p/jwysiwyg/issues/detail?id=15
			formHeight: 270,
			formWidth: 440,
			iFrameClass: null,
			initialContent: "<p>Initial content</p>",
			maxHeight: 10000,			// see autoGrow
			maxLength: 0,
			messages: {
				nonSelection: "Select the text you wish to link"
			},
			toolbarHtml: '<ul role="menu" class="toolbar"></ul>',
			removeHeadings: false,
			replaceDivWithP: false,
			resizeOptions: false,
			rmUnusedControls: false,	// https://github.com/akzhan/jwysiwyg/issues/52
			rmUnwantedBr: true,			// http://code.google.com/p/jwysiwyg/issues/detail?id=11
			tableFiller: "Lorem ipsum",
			initialMinHeight: null,

			controlImage: {
				forceRelativeUrls: false
			},

			controlLink: {
				forceRelativeUrls: false
			},

			plugins: { // placeholder for plugins settings
				autoload: false,
				i18n: false,
				rmFormat: {
					rmMsWordMarkup: false
				}
			}
		};

		this.availableControlProperties = [
			"arguments",
			"callback",
			"className",
			"command",
			"css",
			"custom",
			"exec",
			"groupIndex",
			"hotkey",
			"icon",
			"tags",
			"tooltip",
			"visible"
		];

		this.editor			= null;
		this.editorDoc		= null;
		this.element		= null;
		this.options		= {};
		this.original		= null;
		this.savedRange		= null;
		this.timers			= [];
		this.validKeyCodes	= [8, 9, 13, 16, 17, 18, 19, 20, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 46];

		this.isDestroyed	= false;

		this.dom		= { // DOM related properties and methods
			ie:		{
				parent: null // link to dom
			},
			w3c:	{
				parent: null // link to dom
			}
		};
		this.dom.parent		= this;
		this.dom.ie.parent	= this.dom;
		this.dom.w3c.parent	= this.dom;

		this.ui			= {};	// UI related properties and methods
		this.ui.self	= this;
		this.ui.toolbar	= null;
		this.ui.initialHeight = null; // ui.grow

		this.dom.getAncestor = function (element, filterTagName) {
			filterTagName = filterTagName.toLowerCase();

			while (element && "body" !== element.tagName.toLowerCase()) {
				if (filterTagName === element.tagName.toLowerCase()) {
					return element;
				}

				element = element.parentNode;
			}

			return null;
		};

		this.dom.getElement = function (filterTagName) {
			var dom = this;

			if (window.getSelection) {
				return dom.w3c.getElement(filterTagName);
			} else {
				return dom.ie.getElement(filterTagName);
			}
		};

		this.dom.ie.getElement = function (filterTagName) {
			var dom			= this.parent,
				selection	= dom.parent.getInternalSelection(),
				range		= selection.createRange(),
				element;

			if ("Control" === selection.type) {
				// control selection
				if (1 === range.length) {
					element = range.item(0);
				} else {
					// multiple control selection
					return null;
				}
			} else {
				element = range.parentElement();
			}

			return dom.getAncestor(element, filterTagName);
		};

		this.dom.w3c.getElement = function (filterTagName) {
			var dom		= this.parent,
				range	= dom.parent.getInternalRange(),
				element;

			if (!range) {
				return null;
			}

			element	= range.commonAncestorContainer;

			if (3 === element.nodeType) {
				element = element.parentNode;
			}

			// if startContainer not Text, Comment, or CDATASection element then
			// startOffset is the number of child nodes between the start of the
			// startContainer and the boundary point of the Range
			if (element === range.startContainer) {
				element = element.childNodes[range.startOffset];
			}

			return dom.getAncestor(element, filterTagName);
		};

		this.ui.addHoverClass = function () {
			$(this).addClass("wysiwyg-button-hover");
		};

		this.ui.appendControls = function () {
			if(this.alreadyInit) return;
			this.alreadyInit = true;

			var ui = this,
				self = this.self,
				controls = self.parseControls(),
				hasVisibleControls	= true, // to prevent separator before first item
				groups = [],
				controlsByGroup = {},
				i,
				currentGroupIndex, // jslint wants all vars at top of function
				iterateGroup = function (controlName, control) {
					if (control.groupIndex && currentGroupIndex !== control.groupIndex) {
						currentGroupIndex = control.groupIndex;
						hasVisibleControls = false;
					}

					if (!control.visible) {
						return;
					}

					if (!hasVisibleControls) {
						ui.appendItemSeparator();
						hasVisibleControls = true;
					}

					if (control.custom) {
						ui.appendItemCustom(controlName, control);
					} else {
						ui.appendItem(controlName, control);
					}
				};

			$.each(controls, function (name, c) {
				var index = "empty";

				if (undefined !== c.groupIndex) {
					if ("" === c.groupIndex) {
						index = "empty";
					} else {
						index = c.groupIndex;
					}
				}

				if (undefined === controlsByGroup[index]) {
					groups.push(index);
					controlsByGroup[index] = {};
				}
				controlsByGroup[index][name] = c;
			});

			groups.sort(function (a, b) {
				if ("number" === typeof (a) && typeof (a) === typeof (b)) {
					return (a - b);
				} else {
					a = a.toString();
					b = b.toString();

					if (a > b) {
						return 1;
					}

					if (a === b) {
						return 0;
					}

					return -1;
				}
			});

			if (0 < groups.length) {
				// set to first index in groups to proper placement of separator
				currentGroupIndex = groups[0];
			}

			for (i = 0; i < groups.length; i += 1) {
				$.each(controlsByGroup[groups[i]], iterateGroup);
			}
		};

		this.ui.appendItem = function (name, control) {
			var self = this.self,
				className = control.className || control.command || name || "empty",
				tooltip = control.tooltip || control.command || name || "";

			return $('<li role="menuitem" unselectable="on">' + (className) + "</li>")
				.addClass(className)
				.attr("title", tooltip)
				.hover(this.addHoverClass, this.removeHoverClass)
				.click(function () {
					if ($(this).hasClass("disabled")) {
						return false;
					}

					self.triggerControl.apply(self, [name, control]);

					this.blur();
					self.ui.returnRange();
					self.ui.focus();
					return true;
				})
				.appendTo(self.ui.toolbar);
		};

		this.ui.appendItemCustom = function (name, control) {
			var self = this.self,
				tooltip = control.tooltip || control.command || name || "";

			if (control.callback) {
				$(window).bind("trigger-" + name + ".wysiwyg", control.callback);
			}

			return $('<li role="menuitem" unselectable="on" style="background: url(\'' + control.icon + '\') no-repeat;"></li>')
				.addClass("custom-command-" + name)
				.addClass("wysiwyg-custom-command")
				.addClass(name)
				.attr("title", tooltip)
				.hover(this.addHoverClass, this.removeHoverClass)
				.click(function () {
					if ($(this).hasClass("disabled")) {
						return false;
					}

					self.triggerControl.apply(self, [name, control]);

					this.blur();
					self.ui.returnRange();
					self.ui.focus();

					self.triggerControlCallback(name);
					return true;
				})
				.appendTo(self.ui.toolbar);
		};

		this.ui.appendItemSeparator = function () {
			var self = this.self;
			return $('<li role="separator" class="separator"></li>').appendTo(self.ui.toolbar);
		};

		this.autoSaveFunction = function () {
			this.saveContent();
		};

		this.ui.checkTargets = function (element) {
			var self = this.self;

			$.each(self.options.controls, function (name, control) {
				var className = control.className || control.command || name || "empty",
					tags,
					elm,
					css,
					el,
					checkActiveStatus = function (cssProperty, cssValue) {
						var handler;

						if ("function" === typeof (cssValue)) {
							handler = cssValue;
							if (handler(el.css(cssProperty).toString().toLowerCase(), self)) {
								self.ui.toolbar.find("." + className).addClass("active");
							}
						} else {
							if (el.css(cssProperty).toString().toLowerCase() === cssValue) {
								self.ui.toolbar.find("." + className).addClass("active");
							}
						}
					};

				if ("fullscreen" !== className) {
					self.ui.toolbar.find("." + className).removeClass("active");
				}

				if (control.tags || (control.options && control.options.tags)) {
					tags = control.tags || (control.options && control.options.tags);

					elm = element;
					while (elm) {
						if (elm.nodeType !== 1) {
							break;
						}

						if ($.inArray(elm.tagName.toLowerCase(), tags) !== -1) {
							self.ui.toolbar.find("." + className).addClass("active");
						}

						elm = elm.parentNode;
					}
				}

				if (control.css || (control.options && control.options.css)) {
					css = control.css || (control.options && control.options.css);
					el = $(element);

					while (el) {
						if (el[0].nodeType !== 1) {
							break;
						}
						$.each(css, checkActiveStatus);

						el = el.parent();
					}
				}
			});
		};

		this.ui.designMode = function () {
			var attempts = 3,
				self = this.self,
				runner;
				runner = function (attempts) {
					if ("on" === self.editorDoc.designMode) {
						if (self.timers.designMode) {
							window.clearTimeout(self.timers.designMode);
						}

						// IE needs to reget the document element (this.editorDoc) after designMode was set
						if (self.innerDocument() !== self.editorDoc) {
							self.ui.initFrame();
						}

						return;
					}

					try {
						self.editorDoc.designMode = "on";
					} catch (e) {
					}

					attempts -= 1;
					if (attempts > 0) {
						self.timers.designMode = window.setTimeout(function () { runner(attempts); }, 100);
					}
				};

			runner(attempts);
		};

		this.destroy = function () {
			this.isDestroyed = true;

			var i, $form = this.element.closest("form");

			for (i = 0; i < this.timers.length; i += 1) {
				window.clearTimeout(this.timers[i]);
			}

			// Remove bindings
			$form.unbind(".wysiwyg");
			this.element.remove();
			$.removeData(this.original, "wysiwyg");
			$(this.original).show();
			return this;
		};

		this.getRangeText = function () {
			var r = this.getInternalRange();

			if (r.toString) {
				r = r.toString();
			} else if (r.text) {	// IE
				r = r.text;
			}

			return r;
		};
		//not used?
		this.execute = function (command, arg) {
			if (typeof (arg) === "undefined") {
				arg = null;
			}
			this.editorDoc.execCommand(command, false, arg);
		};

		this.extendOptions = function (options) {
			var controls = {};

			/**
			 * If the user set custom controls, we catch it, and merge with the
			 * defaults controls later.
			 */
			if ("object" === typeof options.controls) {
				controls = options.controls;
				delete options.controls;
			}

			options = $.extend(true, {}, this.defaults, options);
			options.controls = $.extend(true, {}, controls, this.controls, controls);

			if (options.rmUnusedControls) {
				$.each(options.controls, function (controlName) {
					if (!controls[controlName]) {
						delete options.controls[controlName];
					}
				});
			}

			return options;
		};

		this.ui.focus = function () {
			var self = this.self;

			self.editor.get(0).contentWindow.focus();
			return self;
		};

		this.ui.returnRange = function () {
			var self = this.self, sel;

			if (self.savedRange !== null) {
				if (window.getSelection) { //non IE and there is already a selection
					sel = window.getSelection();
					if (sel.rangeCount > 0) {
						sel.removeAllRanges();
					}
					try {
						sel.addRange(self.savedRange);
					} catch (e) {
						console.error(e);
					}
				} else if (window.document.createRange) { // non IE and no selection
					window.getSelection().addRange(self.savedRange);
				} else if (window.document.selection) { //IE
					self.savedRange.select();
				}

				self.savedRange = null;
			}
		};

		this.increaseFontSize = function () {
			if ($.browser.mozilla || $.browser.opera) {
				this.editorDoc.execCommand('increaseFontSize', false, null);
			} else if ($.browser.safari) {
				var newNode = this.editorDoc.createElement('big');
				this.getInternalRange().surroundContents(newNode);
			} else {
				console.error("Internet Explorer?");
			}
		};

		this.decreaseFontSize = function () {
			if ($.browser.mozilla || $.browser.opera) {
				this.editorDoc.execCommand('decreaseFontSize', false, null);
			} else if ($.browser.safari) {
				var newNode = this.editorDoc.createElement('small');
				this.getInternalRange().surroundContents(newNode);
			} else {
				console.error("Internet Explorer?");
			}
		};

		this.getContent = function () {
			return this.events.filter('getContent', this.editorDoc.body.innerHTML);
		};

		/**
		 * A jWysiwyg specific event system.
		 *
		 * Example:
		 *
		 * $("#editor").getWysiwyg().events.bind("getContent", function (orig) {
		 *     return "<div id='content'>"+orgi+"</div>";
		 * });
		 *
		 * This makes it so that when ever getContent is called, it is wrapped in a div#content.
		 */
		this.events = {
			_events : {},

			/**
			 * Similar to jQuery's bind, but for jWysiwyg only.
			 */
			bind : function (eventName, callback) {
				if (typeof (this._events.eventName) !== "object") {
					this._events[eventName] = [];
				}
				this._events[eventName].push(callback);
			},

			/**
			 * Similar to jQuery's trigger, but for jWysiwyg only.
			 */
			trigger : function (eventName, args) {
				if (typeof (this._events.eventName) === "object") {
					var editor = this.editor;
					$.each(this._events[eventName], function (k, v) {
						if (typeof (v) === "function") {
							v.apply(editor, args);
						}
					});
				}
			},

			/**
			 * This "filters" `originalText` by passing it as the first argument to every callback
			 * with the name `eventName` and taking the return value and passing it to the next function.
			 *
			 * This function returns the result after all the callbacks have been applied to `originalText`.
			 */
			filter : function (eventName, originalText) {
				if (typeof (this._events[eventName]) === "object") {
					var editor = this.editor,
						args = Array.prototype.slice.call(arguments, 1);

					$.each(this._events[eventName], function (k, v) {
						if (typeof (v) === "function") {
							originalText = v.apply(editor, args);
						}
					});
				}
				return originalText;
			}
		};

		this.getElementByAttributeValue = function (tagName, attributeName, attributeValue) {
			var i, value, elements = this.editorDoc.getElementsByTagName(tagName);

			for (i = 0; i < elements.length; i += 1) {
				value = elements[i].getAttribute(attributeName);

				if ($.browser.msie) {
					/** IE add full path, so I check by the last chars. */
					value = value.substr(value.length - attributeValue.length);
				}

				if (value === attributeValue) {
					return elements[i];
				}
			}

			return false;
		};

		this.getInternalRange = function () {
			var selection = this.getInternalSelection();

			if (!selection) {
				return null;
			}

			if (selection.rangeCount && selection.rangeCount > 0) { // w3c
				return selection.getRangeAt(0);
			} else if (selection.createRange) { // ie
				return selection.createRange();
			}

			return null;
		};

		this.getInternalSelection = function () {
			// firefox: document.getSelection is deprecated
			if (this.editor.get(0).contentWindow) {
				if (this.editor.get(0).contentWindow.getSelection) {
					return this.editor.get(0).contentWindow.getSelection();
				}
				if (this.editor.get(0).contentWindow.selection) {
					return this.editor.get(0).contentWindow.selection;
				}
			}
			if (this.editorDoc.getSelection) {
				return this.editorDoc.getSelection();
			}
			if (this.editorDoc.selection) {
				return this.editorDoc.selection;
			}

			return null;
		};

		this.getRange = function () {
			var selection = this.getSelection();

			if (!selection) {
				return null;
			}

			if (selection.rangeCount && selection.rangeCount > 0) { // w3c
				selection.getRangeAt(0);
			} else if (selection.createRange) { // ie
				return selection.createRange();
			}

			return null;
		};

		this.getSelection = function () {
			return (window.getSelection) ? window.getSelection() : window.document.selection;
		};

		// :TODO: you can type long string and letters will be hidden because of overflow
		this.ui.grow = function () {
			var self = this.self,
				innerBody = $(self.editorDoc.body),
				innerHeight = $.browser.msie ? innerBody[0].scrollHeight : innerBody.height() + 2 + 20, // 2 - borders, 20 - to prevent content jumping on grow
				minHeight = self.ui.initialHeight,
				height = Math.max(innerHeight, minHeight);

			height = Math.min(height, self.options.maxHeight);

			self.editor.attr("scrolling", height < self.options.maxHeight ? "no" : "auto"); // hide scrollbar firefox
			innerBody.css("overflow", height < self.options.maxHeight ? "hidden" : ""); // hide scrollbar chrome

			self.editor.get(0).height = height;

			return self;
		};

		this.init = function (element, options) {
			var self = this,
				$form = $(element).closest("form"),
				newX = element.width || element.clientWidth || 0,
				newY = element.height || element.clientHeight || 0
				;

			this.options	= this.extendOptions(options);
			this.original	= element;
			this.ui.toolbar	= $(this.options.toolbarHtml);

			if ($.browser.msie && parseInt($.browser.version, 10) < 8) {
				this.options.autoGrow = false;
			}

			if (newX === 0 && element.cols) {
				newX = (element.cols * 8) + 21;
			}
			if (newY === 0 && element.rows) {
				newY = (element.rows * 16) + 16;
			}

			this.editor = $(window.location.protocol === "https:" ? '<iframe src="javascript:false;"></iframe>' : "<iframe></iframe>").attr("frameborder", "0");

			if (this.options.iFrameClass) {
				this.editor.addClass(this.options.iFrameClass);
			} else {
				this.editor.css({
					minHeight: (newY - 6).toString() + "px",
					// fix for issue 12 ( http://github.com/akzhan/jwysiwyg/issues/issue/12 )
					width: (newX > 50) ? (newX - 8).toString() + "px" : ""
				});
				if ($.browser.msie && parseInt($.browser.version, 10) < 7) {
					this.editor.css("height", newY.toString() + "px");
				}
			}
			/**
			 * http://code.google.com/p/jwysiwyg/issues/detail?id=96
			 */
			this.editor.attr("tabindex", $(element).attr("tabindex"));

			this.element = $("<div/>").addClass("wysiwyg");

			if (!this.options.iFrameClass) {
				this.element.css({
					width: (newX > 0) ? newX.toString() + "px" : "100%"
				});
			}

			$(element).hide().before(this.element);

			this.viewHTML = false;

			/**
			 * @link http://code.google.com/p/jwysiwyg/issues/detail?id=52
			 */
			this.initialContent = $(element).val();
			this.ui.initFrame();

			if (this.options.resizeOptions && $.fn.resizable) {
				this.element.resizable($.extend(true, {
					alsoResize: this.editor
				}, this.options.resizeOptions));
			}

			if (this.options.autoSave) {
				$form.bind("submit.wysiwyg", function () { self.autoSaveFunction(); });
			}

			$form.bind("reset.wysiwyg", function () { self.resetFunction(); });
		};

		this.ui.initFrame = function () {
			var self = this.self,
				stylesheet,
				growHandler,
				saveHandler;

			self.ui.appendControls();
			self.element.append(self.ui.toolbar)
				.append($("<div><!-- --></div>")
					.css({
						clear: "both"
					}))
				.append(self.editor);

			self.editorDoc = self.innerDocument();

			if (self.isDestroyed) {
				return null;
			}

			self.ui.designMode();
			self.editorDoc.open();
			self.editorDoc.write(
				self.options.html
					/**
					 * @link http://code.google.com/p/jwysiwyg/issues/detail?id=144
					 */
					.replace(/INITIAL_CONTENT/, function () { return self.wrapInitialContent(); })
			);
			self.editorDoc.close();

			$.wysiwyg.plugin.bind(self);

			$(self.editorDoc).trigger("initFrame.wysiwyg");

			$(self.editorDoc).bind("click.wysiwyg", function (event) {
				self.ui.checkTargets(event.target ? event.target : event.srcElement);
			});

			/**
			 * @link http://code.google.com/p/jwysiwyg/issues/detail?id=20
			 */
			$(self.original).focus(function () {
				if ($(this).filter(":visible")) {
					return;
				}
				self.ui.focus();
			});

			$(self.editorDoc).keydown(function (event) {
				var emptyContentRegex;
				if (event.keyCode === 8) { // backspace
					emptyContentRegex = /^<([\w]+)[^>]*>(<br\/?>)?<\/\1>$/;
					if (emptyContentRegex.test(self.getContent())) { // if content is empty
						event.stopPropagation(); // prevent remove single empty tag
						return false;
					}
				}
				return true;
			});

			if (!$.browser.msie) {
				$(self.editorDoc).keydown(function (event) {
					var controlName;

					/* Meta for Macs. tom@punkave.com */
					if (event.ctrlKey || event.metaKey) {
						for (controlName in self.controls) {
							if (self.controls[controlName].hotkey && self.controls[controlName].hotkey.ctrl) {
								if (event.keyCode === self.controls[controlName].hotkey.key) {
									self.triggerControl.apply(self, [controlName, self.controls[controlName]]);

									return false;
								}
							}
						}
					}

					return true;
				});
			} else if (self.options.brIE) {
				$(self.editorDoc).keydown(function (event) {
					if (event.keyCode === 13) {
						var rng = self.getRange();
						rng.pasteHTML("<br/>");
						rng.collapse(false);
						rng.select();

						return false;
					}

					return true;
				});
			}

			if (self.options.plugins.rmFormat.rmMsWordMarkup) {
				$(self.editorDoc).bind("keyup.wysiwyg", function (event) {
					if (event.ctrlKey || event.metaKey) {
						// CTRL + V (paste)
						if (86 === event.keyCode) {
							if ($.wysiwyg.rmFormat) {
								if ("object" === typeof (self.options.plugins.rmFormat.rmMsWordMarkup)) {
									$.wysiwyg.rmFormat.run(self, {rules: { msWordMarkup: self.options.plugins.rmFormat.rmMsWordMarkup }});
								} else {
									$.wysiwyg.rmFormat.run(self, {rules: { msWordMarkup: { enabled: true }}});
								}
							}
						}
					}
				});
			}

			if (self.options.autoSave) {
				$(self.editorDoc).keydown(function () { self.autoSaveFunction(); })
					.keyup(function () { self.autoSaveFunction(); })
					.mousedown(function () { self.autoSaveFunction(); })
					.bind($.support.noCloneEvent ? "input.wysiwyg" : "paste.wysiwyg", function () { self.autoSaveFunction(); });
			}

			if (self.options.autoGrow) {
				if (self.options.initialMinHeight !== null) {
					self.ui.initialHeight = self.options.initialMinHeight;
				} else {
					self.ui.initialHeight = $(self.editorDoc).height();
				}
				$(self.editorDoc.body).css("border", "1px solid white"); // cancel margin collapsing

				growHandler = function () {
					self.ui.grow();
				};

				$(self.editorDoc).keyup(growHandler);
				$(self.editorDoc).bind("editorRefresh.wysiwyg", growHandler);

				// fix when content height > textarea height
				self.ui.grow();
			}

			if (self.options.css) {
				if (String === self.options.css.constructor) {
					if ($.browser.msie) {
						stylesheet = self.editorDoc.createStyleSheet(self.options.css);
						$(stylesheet).attr({
							"media":	"all"
						});
					} else {
						stylesheet = $("<link/>").attr({
							"href":		self.options.css,
							"media":	"all",
							"rel":		"stylesheet",
							"type":		"text/css"
						});

						$(self.editorDoc).find("head").append(stylesheet);
					}
				} else {
					self.timers.initFrame_Css = window.setTimeout(function () {
						$(self.editorDoc.body).css(self.options.css);
					}, 0);
				}
			}

			if (self.initialContent.length === 0) {
				if ("function" === typeof (self.options.initialContent)) {
					self.setContent(self.options.initialContent());
				} else {
					self.setContent(self.options.initialContent);
				}
			}

			if (self.options.maxLength > 0) {
				$(self.editorDoc).keydown(function (event) {
					if ($(self.editorDoc).text().length >= self.options.maxLength && $.inArray(event.which, self.validKeyCodes) === -1) {
						event.preventDefault();
					}
				});
			}

			// Support event callbacks
			$.each(self.options.events, function (key, handler) {
				$(self.editorDoc).bind(key + ".wysiwyg", function (event) {
					// Trigger event handler, providing the event and api to
					// support additional functionality.
					handler.apply(self.editorDoc, [event, self]);
				});
			});

			// restores selection properly on focus
			if ($.browser.msie) {
				// Event chain: beforedeactivate => focusout => blur.
				// Focusout & blur fired too late to handle internalRange() in dialogs.
				// When clicked on input boxes both got range = null
				$(self.editorDoc).bind("beforedeactivate.wysiwyg", function () {
					self.savedRange = self.getInternalRange();
				});
			} else {
				$(self.editorDoc).bind("blur.wysiwyg", function () {
					self.savedRange = self.getInternalRange();
				});
			}

			$(self.editorDoc.body).addClass("wysiwyg");
			if (self.options.events && self.options.events.save) {
				saveHandler = self.options.events.save;

				$(self.editorDoc).bind("keyup.wysiwyg", saveHandler);
				$(self.editorDoc).bind("change.wysiwyg", saveHandler);

				if ($.support.noCloneEvent) {
					$(self.editorDoc).bind("input.wysiwyg", saveHandler);
				} else {
					$(self.editorDoc).bind("paste.wysiwyg", saveHandler);
					$(self.editorDoc).bind("cut.wysiwyg", saveHandler);
				}
			}

			/**
			 * XHTML5 {@link https://github.com/akzhan/jwysiwyg/issues/152}
			 */
			if (self.options.xhtml5 && self.options.unicode) {
				var replacements = {ne:8800,le:8804,para:182,xi:958,darr:8595,nu:957,oacute:243,Uacute:218,omega:969,prime:8242,pound:163,igrave:236,thorn:254,forall:8704,emsp:8195,lowast:8727,brvbar:166,alefsym:8501,nbsp:160,delta:948,clubs:9827,lArr:8656,Omega:937,Auml:196,cedil:184,and:8743,plusmn:177,ge:8805,raquo:187,uml:168,equiv:8801,laquo:171,rdquo:8221,Epsilon:917,divide:247,fnof:402,chi:967,Dagger:8225,iacute:237,rceil:8969,sigma:963,Oslash:216,acute:180,frac34:190,lrm:8206,upsih:978,Scaron:352,part:8706,exist:8707,nabla:8711,image:8465,prop:8733,zwj:8205,omicron:959,aacute:225,Yuml:376,Yacute:221,weierp:8472,rsquo:8217,otimes:8855,kappa:954,thetasym:977,harr:8596,Ouml:214,Iota:921,ograve:242,sdot:8901,copy:169,oplus:8853,acirc:226,sup:8835,zeta:950,Iacute:205,Oacute:211,crarr:8629,Nu:925,bdquo:8222,lsquo:8216,apos:39,Beta:914,eacute:233,egrave:232,lceil:8968,Kappa:922,piv:982,Ccedil:199,ldquo:8220,Xi:926,cent:162,uarr:8593,hellip:8230,Aacute:193,ensp:8194,sect:167,Ugrave:217,aelig:230,ordf:170,curren:164,sbquo:8218,macr:175,Phi:934,Eta:919,rho:961,Omicron:927,sup2:178,euro:8364,aring:229,Theta:920,mdash:8212,uuml:252,otilde:245,eta:951,uacute:250,rArr:8658,nsub:8836,agrave:224,notin:8713,ndash:8211,Psi:936,Ocirc:212,sube:8838,szlig:223,micro:181,not:172,sup1:185,middot:183,iota:953,ecirc:234,lsaquo:8249,thinsp:8201,sum:8721,ntilde:241,scaron:353,cap:8745,atilde:227,lang:10216,__replacement:65533,isin:8712,gamma:947,Euml:203,ang:8736,upsilon:965,Ntilde:209,hearts:9829,Alpha:913,Tau:932,spades:9824,dagger:8224,THORN:222,"int":8747,lambda:955,Eacute:201,Uuml:220,infin:8734,rlm:8207,Aring:197,ugrave:249,Egrave:200,Acirc:194,rsaquo:8250,ETH:208,oslash:248,alpha:945,Ograve:210,Prime:8243,mu:956,ni:8715,real:8476,bull:8226,beta:946,icirc:238,eth:240,prod:8719,larr:8592,ordm:186,perp:8869,Gamma:915,reg:174,ucirc:251,Pi:928,psi:968,tilde:732,asymp:8776,zwnj:8204,Agrave:192,deg:176,AElig:198,times:215,Delta:916,sim:8764,Otilde:213,Mu:924,uArr:8657,circ:710,theta:952,Rho:929,sup3:179,diams:9830,tau:964,Chi:935,frac14:188,oelig:339,shy:173,or:8744,dArr:8659,phi:966,iuml:239,Lambda:923,rfloor:8971,iexcl:161,cong:8773,ccedil:231,Icirc:206,frac12:189,loz:9674,rarr:8594,cup:8746,radic:8730,frasl:8260,euml:235,OElig:338,hArr:8660,Atilde:195,Upsilon:933,there4:8756,ouml:246,oline:8254,Ecirc:202,yacute:253,auml:228,permil:8240,sigmaf:962,iquest:191,empty:8709,pi:960,Ucirc:219,supe:8839,Igrave:204,yen:165,rang:10217,trade:8482,lfloor:8970,minus:8722,Zeta:918,sub:8834,epsilon:949,yuml:255,Sigma:931,Iuml:207,ocirc:244};
				self.events.bind("getContent", function (text) {
					return text.replace(/&(?:amp;)?(?!amp|lt|gt|quot)([a-z][a-z0-9]*);/gi, function (str, p1) {
						if (!replacements[p1]) {
							p1 = p1.toLowerCase();
							if (!replacements[p1]) {
								p1 = "__replacement";
							}
						}

						var num = replacements[p1];
						/* Numeric return if ever wanted: return replacements[p1] ? "&#"+num+";" : ""; */
						return String.fromCharCode(num);
					});
				});
			}
		};

		this.innerDocument = function () {
			var element = this.editor.get(0);

			if (element.nodeName.toLowerCase() === "iframe") {
				if (element.contentDocument) {				// Gecko
					return element.contentDocument;
				} else if (element.contentWindow) {			// IE
					return element.contentWindow.document;
				}

				if (this.isDestroyed) {
					return null;
				}

				console.error("Unexpected error in innerDocument");

				/*
				 return ( $.browser.msie )
				 ? document.frames[element.id].document
				 : element.contentWindow.document // contentDocument;
				 */
			}

			return element;
		};

		this.insertHtml = function (szHTML) {
			var img, range;

			if (!szHTML || szHTML.length === 0) {
				return this;
			}

			if ($.browser.msie) {
				this.ui.focus();
				this.editorDoc.execCommand("insertImage", false, "#jwysiwyg#");
				img = this.getElementByAttributeValue("img", "src", "#jwysiwyg#");
				if (img) {
					$(img).replaceWith(szHTML);
				}
			} else {
				if ($.browser.mozilla) { // @link https://github.com/akzhan/jwysiwyg/issues/50
					if (1 === $(szHTML).length) {
						range = this.getInternalRange();
						range.deleteContents();
						range.insertNode($(szHTML).get(0));
					} else {
						this.editorDoc.execCommand("insertHTML", false, szHTML);
					}
				} else {
					if (!this.editorDoc.execCommand("insertHTML", false, szHTML)) {
						this.editor.focus();
						/* :TODO: place caret at the end
						if (window.getSelection) {
						} else {
						}
						this.editor.focus();
						*/
						this.editorDoc.execCommand("insertHTML", false, szHTML);
					}
				}
			}

			this.saveContent();

			return this;
		};

		this.parseControls = function () {
			var self = this;

			$.each(this.options.controls, function (controlName, control) {
				$.each(control, function (propertyName) {
					if (-1 === $.inArray(propertyName, self.availableControlProperties)) {
						throw controlName + '["' + propertyName + '"]: property "' + propertyName + '" not exists in Wysiwyg.availableControlProperties';
					}
				});
			});

			if (this.options.parseControls) {
				return this.options.parseControls.call(this);
			}

			return this.options.controls;
		};

		this.removeFormat = function () {
			if ($.browser.msie) {
				this.ui.focus();
			}

			if (this.options.removeHeadings) {
				this.editorDoc.execCommand("formatBlock", false, "<p>"); // remove headings
			}

			this.editorDoc.execCommand("removeFormat", false, null);
			this.editorDoc.execCommand("unlink", false, null);

			if ($.wysiwyg.rmFormat && $.wysiwyg.rmFormat.enabled) {
				if ("object" === typeof (this.options.plugins.rmFormat.rmMsWordMarkup)) {
					$.wysiwyg.rmFormat.run(this, {rules: { msWordMarkup: this.options.plugins.rmFormat.rmMsWordMarkup }});
				} else {
					$.wysiwyg.rmFormat.run(this, {rules: { msWordMarkup: { enabled: true }}});
				}
			}

			return this;
		};

		this.ui.removeHoverClass = function () {
			$(this).removeClass("wysiwyg-button-hover");
		};

		this.resetFunction = function () {
			this.setContent(this.initialContent);
		};

		this.saveContent = function () {
			if (this.original) {
				var content, newContent;

				content = this.getContent();

				if (this.options.rmUnwantedBr) {
					content = content.replace(/<br\/?>$/, "");
				}

				if (this.options.replaceDivWithP) {
					newContent = $("<div/>").addClass("temp").append(content);

					newContent.children("div").each(function () {
						var element = $(this), p = element.find("p"), i;

						if (0 === p.length) {
							p = $("<p></p>");

							if (this.attributes.length > 0) {
								for (i = 0; i < this.attributes.length; i += 1) {
									p.attr(this.attributes[i].name, element.attr(this.attributes[i].name));
								}
							}

							p.append(element.html());

							element.replaceWith(p);
						}
					});

					content = newContent.html();
				}

				$(this.original).val(content);

				if (this.options.events && this.options.events.save) {
					this.options.events.save.call(this);
				}
			}

			return this;
		};

		this.setContent = function (newContent) {
			this.editorDoc.body.innerHTML = newContent;
			this.saveContent();

			return this;
		};

		this.triggerControl = function (name, control) {
			var cmd = control.command || name,
				args = control["arguments"] || [];

			if (control.exec) {
				control.exec.apply(this);
			} else {
				this.ui.focus();
				this.ui.withoutCss();
				// when click <Cut>, <Copy> or <Paste> got "Access to XPConnect service denied" code: "1011"
				// in Firefox untrusted JavaScript is not allowed to access the clipboard
				try {
					this.editorDoc.execCommand(cmd, false, args);
				} catch (e) {
					console.error(e);
				}
			}

			if (this.options.autoSave) {
				this.autoSaveFunction();
			}
		};

		this.triggerControlCallback = function (name) {
			$(window).trigger("trigger-" + name + ".wysiwyg", [this]);
		};

		this.ui.withoutCss = function () {
			var self = this.self;

			if ($.browser.mozilla) {
				try {
					self.editorDoc.execCommand("styleWithCSS", false, false);
				} catch (e) {
					try {
						self.editorDoc.execCommand("useCSS", false, true);
					} catch (e2) {
					}
				}
			}

			return self;
		};

		this.wrapInitialContent = function () {
			var content = this.initialContent,
				found = content.match(/<\/?p>/gi);

			if (!found) {
				return "<p>" + content + "</p>";
			} else {
				// :TODO: checking/replacing
			}

			return content;
		};
	}

	/*
	 * Wysiwyg namespace: public properties and methods
	 */
	$.wysiwyg = {
		messages: {
			noObject: "Something goes wrong, check object"
		},

		/**
		 * Custom control support by Alec Gorge ( http://github.com/alecgorge )
		 */
		addControl: function (object, name, settings) {
			return object.each(function () {
				var oWysiwyg = $(this).data("wysiwyg"),
					customControl = {},
					toolbar;

				if (!oWysiwyg) {
					return this;
				}

				customControl[name] = $.extend(true, {visible: true, custom: true}, settings);
				$.extend(true, oWysiwyg.options.controls, customControl);

				// render new toolbar
				toolbar = $(oWysiwyg.options.toolbarHtml);
				oWysiwyg.ui.toolbar.replaceWith(toolbar);
				oWysiwyg.ui.toolbar = toolbar;
				oWysiwyg.ui.appendControls();
			});
		},

		clear: function (object) {
			return object.each(function () {
				var oWysiwyg = $(this).data("wysiwyg");

				if (!oWysiwyg) {
					return this;
				}

				oWysiwyg.setContent("");
			});
		},

		console: console, // let our console be available for extensions

		destroy: function (object) {
			return object.each(function () {
				var oWysiwyg = $(this).data("wysiwyg");

				if (!oWysiwyg) {
					return this;
				}

				oWysiwyg.destroy();
			});
		},

		"document": function (object) {
			// no chains because of return
			var oWysiwyg = object.data("wysiwyg");

			if (!oWysiwyg) {
				return undefined;
			}

			return $(oWysiwyg.editorDoc);
		},

		getContent: function (object) {
			// no chains because of return
			var oWysiwyg = object.data("wysiwyg");

			if (!oWysiwyg) {
				return undefined;
			}

			return oWysiwyg.getContent();
		},

		init: function (object, options) {
			return object.each(function () {
				var opts = $.extend(true, {}, options),
					obj;

				// :4fun:
				// remove this textarea validation and change line in this.saveContent function
				// $(this.original).val(content); to $(this.original).html(content);
				// now you can make WYSIWYG editor on h1, p, and many more tags
				if (("textarea" !== this.nodeName.toLowerCase()) || $(this).data("wysiwyg")) {
					return;
				}

				obj = new Wysiwyg();
				obj.init(this, opts);
				$.data(this, "wysiwyg", obj);

				$(obj.editorDoc).trigger("afterInit.wysiwyg");
			});
		},

		insertHtml: function (object, szHTML) {
			return object.each(function () {
				var oWysiwyg = $(this).data("wysiwyg");

				if (!oWysiwyg) {
					return this;
				}

				oWysiwyg.insertHtml(szHTML);
			});
		},

		plugin: {
			listeners: {},

			bind: function (Wysiwyg) {
				var self = this;

				$.each(this.listeners, function (action, handlers) {
					var i, plugin;

					for (i = 0; i < handlers.length; i += 1) {
						plugin = self.parseName(handlers[i]);

						$(Wysiwyg.editorDoc).bind(action + ".wysiwyg", {plugin: plugin}, function (event) {
							$.wysiwyg[event.data.plugin.name][event.data.plugin.method].apply($.wysiwyg[event.data.plugin.name], [Wysiwyg]);
						});
					}
				});
			},

			exists: function (name) {
				var plugin;

				if ("string" !== typeof (name)) {
					return false;
				}

				plugin = this.parseName(name);

				if (!$.wysiwyg[plugin.name] || !$.wysiwyg[plugin.name][plugin.method]) {
					return false;
				}

				return true;
			},

			listen: function (action, handler) {
				var plugin;

				plugin = this.parseName(handler);

				if (!$.wysiwyg[plugin.name] || !$.wysiwyg[plugin.name][plugin.method]) {
					return false;
				}

				if (!this.listeners[action]) {
					this.listeners[action] = [];
				}

				this.listeners[action].push(handler);

				return true;
			},

			parseName: function (name) {
				var elements;

				if ("string" !== typeof (name)) {
					return false;
				}

				elements = name.split(".");

				if (2 > elements.length) {
					return false;
				}

				return {name: elements[0], method: elements[1]};
			},

			register: function (data) {
				if (!data.name) {
					console.error("Plugin name missing");
				}

				$.each($.wysiwyg, function (pluginName) {
					if (pluginName === data.name) {
						console.error("Plugin with name '" + data.name + "' was already registered");
					}
				});

				$.wysiwyg[data.name] = data;

				return true;
			}
		},

		removeFormat: function (object) {
			return object.each(function () {
				var oWysiwyg = $(this).data("wysiwyg");

				if (!oWysiwyg) {
					return this;
				}

				oWysiwyg.removeFormat();
			});
		},

		save: function (object) {
			return object.each(function () {
				var oWysiwyg = $(this).data("wysiwyg");

				if (!oWysiwyg) {
					return this;
				}

				oWysiwyg.saveContent();
			});
		},

		selectAll: function (object) {
			var oWysiwyg = object.data("wysiwyg"), oBody, oRange, selection;

			if (!oWysiwyg) {
				return this;
			}

			oBody = oWysiwyg.editorDoc.body;
			if (window.getSelection) {
				selection = oWysiwyg.getInternalSelection();
				selection.selectAllChildren(oBody);
			} else {
				oRange = oBody.createTextRange();
				oRange.moveToElementText(oBody);
				oRange.select();
			}
		},

		setContent: function (object, newContent) {
			return object.each(function () {
				var oWysiwyg = $(this).data("wysiwyg");

				if (!oWysiwyg) {
					return this;
				}

				oWysiwyg.setContent(newContent);
			});
		},

		triggerControl: function (object, controlName) {
			return object.each(function () {
				var oWysiwyg = $(this).data("wysiwyg");

				if (!oWysiwyg) {
					return this;
				}

				if (!oWysiwyg.controls[controlName]) {
					console.error("Control '" + controlName + "' not exists");
				}

				oWysiwyg.triggerControl.apply(oWysiwyg, [controlName, oWysiwyg.controls[controlName]]);
			});
		},

		support: {
			prop: supportsProp
		},

		utils: {
			extraSafeEntities: [["<", ">", "'", '"', " "], [32]],

			encodeEntities: function (str) {
				var self = this, aStr, aRet = [];

				if (this.extraSafeEntities[1].length === 0) {
					$.each(this.extraSafeEntities[0], function (i, ch) {
						self.extraSafeEntities[1].push(ch.charCodeAt(0));
					});
				}
				aStr = str.split("");
				$.each(aStr, function (i) {
					var iC = aStr[i].charCodeAt(0);
					if ($.inArray(iC, self.extraSafeEntities[1]) && (iC < 65 || iC > 127 || (iC > 90 && iC < 97))) {
						aRet.push('&#' + iC + ';');
					} else {
						aRet.push(aStr[i]);
					}
				});

				return aRet.join('');
			}
		}
	};

	$.fn.wysiwyg = function (method) {
		var args = arguments, plugin;

		if ("undefined" !== typeof $.wysiwyg[method]) {
			// set argument object to undefined
			args = Array.prototype.concat.call([args[0]], [this], Array.prototype.slice.call(args, 1));
			return $.wysiwyg[method].apply($.wysiwyg, Array.prototype.slice.call(args, 1));
		} else if ("object" === typeof method || !method) {
			Array.prototype.unshift.call(args, this);
			return $.wysiwyg.init.apply($.wysiwyg, args);
		} else if ($.wysiwyg.plugin.exists(method)) {
			plugin = $.wysiwyg.plugin.parseName(method);
			args = Array.prototype.concat.call([args[0]], [this], Array.prototype.slice.call(args, 1));
			return $.wysiwyg[plugin.name][plugin.method].apply($.wysiwyg[plugin.name], Array.prototype.slice.call(args, 1));
		} else {
			console.error("Method '" +  method + "' does not exist on jQuery.wysiwyg.\nTry to include some extra controls or plugins");
		}
	};

	$.fn.getWysiwyg = function () {
		return $.data(this, "wysiwyg");
	};
})(jQuery);

/**
 * Controls: Link plugin
 *
 * Depends on jWYSIWYG
 *
 * By: Esteban Beltran (academo) <sergies@gmail.com>
 */
(function ($) {
	if (undefined === $.wysiwyg) {
		throw "wysiwyg.image.js depends on $.wysiwyg";
	}

	if (!$.wysiwyg.controls) {
		$.wysiwyg.controls = {};
	}

	/*
	* Wysiwyg namespace: public properties and methods
	*/
	$.wysiwyg.controls.link = {
		init: function (Wysiwyg) {
			var self = this, elements, dialog, url, a, selection,
				formLinkHtml, formTextLegend, formTextUrl, formTextTitle, formTextTarget,
				formTextSubmit, formTextReset,
				baseUrl;

			formTextLegend  = "Insert Link";
			formTextUrl     = "Адрес ссылки";
			formTextTitle   = "Link Title";
			formTextTarget  = "Link Target";
			formTextSubmit  = "Insert Link";
			formTextReset   = "Cancel";

			if ($.wysiwyg.i18n) {
				formTextLegend = $.wysiwyg.i18n.t(formTextLegend, "dialogs.link");
				formTextUrl    = $.wysiwyg.i18n.t(formTextUrl, "dialogs.link");
				formTextTitle  = $.wysiwyg.i18n.t(formTextTitle, "dialogs.link");
				formTextTarget = $.wysiwyg.i18n.t(formTextTarget, "dialogs.link");
				formTextSubmit = $.wysiwyg.i18n.t(formTextSubmit, "dialogs.link");
				formTextReset  = $.wysiwyg.i18n.t(formTextReset, "dialogs");
			}

			formLinkHtml = '<form class="wysiwyg"><fieldset><legend>' + formTextLegend + '</legend>' +
				'<label>' + formTextUrl + ': <input type="text" name="linkhref" value=""/></label>' +
				'<label>' + formTextTitle + ': <input type="text" name="linktitle" value=""/></label>' +
				'<label>' + formTextTarget + ': <input type="text" name="linktarget" value=""/></label>' +
				'<input type="submit" class="button" value="' + formTextSubmit + '"/> ' +
				'<input type="reset" value="' + formTextReset + '"/></fieldset></form>';

			a = {
				self: Wysiwyg.dom.getElement("a"), // link to element node
				href: "http://",
				title: "",
				target: ""
			};

			if (a.self) {
				a.href = a.self.href ? a.self.href : a.href;
				a.title = a.self.title ? a.self.title : "";
				a.target = a.self.target ? a.self.target : "";
			}

			if ($.fn.dialog) {
				elements = $(formLinkHtml);
				elements.find("input[name=linkhref]").val(a.href);
				elements.find("input[name=linktitle]").val(a.title);
				elements.find("input[name=linktarget]").val(a.target);

				if ($.browser.msie) {
					dialog = elements.appendTo(Wysiwyg.editorDoc.body);
				} else {
					dialog = elements.appendTo("body");
				}

				dialog.dialog({
					modal: true,
					open: function (ev, ui) {
						$("input:submit", dialog).click(function (e) {
							e.preventDefault();

							var url = $('input[name="linkhref"]', dialog).val(),
								title = $('input[name="linktitle"]', dialog).val(),
								target = $('input[name="linktarget"]', dialog).val(),
								baseUrl;

							if (Wysiwyg.options.controlLink.forceRelativeUrls) {
								baseUrl = window.location.protocol + "//" + window.location.hostname;
								if (0 === url.indexOf(baseUrl)) {
									url = url.substr(baseUrl.length);
								}
							}

							if (a.self) {
								if ("string" === typeof (url)) {
									if (url.length > 0) {
										// to preserve all link attributes
										$(a.self).attr("href", url).attr("title", title).attr("target", target);
									} else {
										$(a.self).replaceWith(a.self.innerHTML);
									}
								}
							} else {
								if ($.browser.msie) {
									Wysiwyg.ui.returnRange();
								}

								//Do new link element
								selection = Wysiwyg.getRangeText();
								img = Wysiwyg.dom.getElement("img");

								if ((selection && selection.length > 0) || img) {
									if ($.browser.msie) {
										Wysiwyg.ui.focus();
									}

									if ("string" === typeof (url)) {
										if (url.length > 0) {
											Wysiwyg.editorDoc.execCommand("createLink", false, url);
										} else {
											Wysiwyg.editorDoc.execCommand("unlink", false, null);
										}
									}

									a.self = Wysiwyg.dom.getElement("a");

									$(a.self).attr("href", url).attr("title", title);

									/**
									 * @url https://github.com/akzhan/jwysiwyg/issues/16
									 */
									$(a.self).attr("target", target);
								} else if (Wysiwyg.options.messages.nonSelection) {
									window.alert(Wysiwyg.options.messages.nonSelection);
								}
							}

							Wysiwyg.saveContent();

							$(dialog).dialog("close");
						});
						$("input:reset", dialog).click(function (e) {
							e.preventDefault();
							$(dialog).dialog("close");
						});
					},
					close: function (ev, ui) {
						dialog.dialog("destroy");
					}
				});
			} else {
				if (a.self) {
					url = window.prompt("URL", a.href);

					if (Wysiwyg.options.controlLink.forceRelativeUrls) {
						baseUrl = window.location.protocol + "//" + window.location.hostname;
						if (0 === url.indexOf(baseUrl)) {
							url = url.substr(baseUrl.length);
						}
					}

					if ("string" === typeof (url)) {
						if (url.length > 0) {
							$(a.self).attr("href", url);
						} else {
							$(a.self).replaceWith(a.self.innerHTML);
						}
					}
				} else {
					//Do new link element
					selection = Wysiwyg.getRangeText();
					img = Wysiwyg.dom.getElement("img");

					if ((selection && selection.length > 0) || img) {
						if ($.browser.msie) {
							Wysiwyg.ui.focus();
							Wysiwyg.editorDoc.execCommand("createLink", true, null);
						} else {
							url = window.prompt(formTextUrl, a.href);

							if (Wysiwyg.options.controlLink.forceRelativeUrls) {
								baseUrl = window.location.protocol + "//" + window.location.hostname;
								if (0 === url.indexOf(baseUrl)) {
									url = url.substr(baseUrl.length);
								}
							}

							if ("string" === typeof (url)) {
								if (url.length > 0) {
									Wysiwyg.editorDoc.execCommand("createLink", false, url);
								} else {
									Wysiwyg.editorDoc.execCommand("unlink", false, null);
								}
							}
						}
					} else if (Wysiwyg.options.messages.nonSelection) {
						window.alert(Wysiwyg.options.messages.nonSelection);
					}
				}

				Wysiwyg.saveContent();
			}

			$(Wysiwyg.editorDoc).trigger("editorRefresh.wysiwyg");
		}
	};

	$.wysiwyg.createLink = function (object, url) {
		return object.each(function () {
			var oWysiwyg = $(this).data("wysiwyg"),
				selection;

			if (!oWysiwyg) {
				return this;
			}

			if (!url || url.length === 0) {
				return this;
			}

			selection = oWysiwyg.getRangeText();

			if (selection && selection.length > 0) {
				if ($.browser.msie) {
					oWysiwyg.ui.focus();
				}
				oWysiwyg.editorDoc.execCommand("unlink", false, null);
				oWysiwyg.editorDoc.execCommand("createLink", false, url);
			} else if (oWysiwyg.options.messages.nonSelection) {
				window.alert(oWysiwyg.options.messages.nonSelection);
			}
		});
	};
})(jQuery);

/**
 * http://github.com/valums/file-uploader
 * 
 * Multiple file upload component with progress-bar, drag-and-drop. 
 * © 2010 Andrew Valums ( andrew(at)valums.com ) 
 * 
 * Licensed under GNU GPL 2 or later, see license.txt.
 */    

//
// Helper functions
//

var qq = qq || {};

/**
 * Adds all missing properties from second obj to first obj
 */ 
qq.extend = function(first, second){
    for (var prop in second){
        first[prop] = second[prop];
    }
};  

/**
 * Searches for a given element in the array, returns -1 if it is not present.
 * @param {Number} [from] The index at which to begin the search
 */
qq.indexOf = function(arr, elt, from){
    if (arr.indexOf) return arr.indexOf(elt, from);
    
    from = from || 0;
    var len = arr.length;    
    
    if (from < 0) from += len;  

    for (; from < len; from++){  
        if (from in arr && arr[from] === elt){  
            return from;
        }
    }  
    return -1;  
}; 
    
qq.getUniqueId = (function(){
    var id = 0;
    return function(){ return id++; };
})();

//
// Events

qq.attach = function(element, type, fn){
    if (element.addEventListener){
        element.addEventListener(type, fn, false);
    } else if (element.attachEvent){
        element.attachEvent('on' + type, fn);
    }
};
qq.detach = function(element, type, fn){
    if (element.removeEventListener){
        element.removeEventListener(type, fn, false);
    } else if (element.attachEvent){
        element.detachEvent('on' + type, fn);
    }
};

qq.preventDefault = function(e){
    if (e.preventDefault){
        e.preventDefault();
    } else{
        e.returnValue = false;
    }
};

//
// Node manipulations

/**
 * Insert node a before node b.
 */
qq.insertBefore = function(a, b){
    b.parentNode.insertBefore(a, b);
};
qq.remove = function(element){
    element.parentNode.removeChild(element);
};

qq.contains = function(parent, descendant){       
    // compareposition returns false in this case
    if (parent == descendant) return true;
    
    if (parent.contains){
        return parent.contains(descendant);
    } else {
        return !!(descendant.compareDocumentPosition(parent) & 8);
    }
};

/**
 * Creates and returns element from html string
 * Uses innerHTML to create an element
 */
qq.toElement = (function(){
    var div = document.createElement('div');
    return function(html){
        div.innerHTML = html;
        var element = div.firstChild;
        div.removeChild(element);
        return element;
    };
})();

//
// Node properties and attributes

/**
 * Sets styles for an element.
 * Fixes opacity in IE6-8.
 */
qq.css = function(element, styles){
    if (styles.opacity != null){
        if (typeof element.style.opacity != 'string' && typeof(element.filters) != 'undefined'){
            styles.filter = 'alpha(opacity=' + Math.round(100 * styles.opacity) + ')';
        }
    }
    qq.extend(element.style, styles);
};
qq.hasClass = function(element, name){
    var re = new RegExp('(^| )' + name + '( |$)');
    return re.test(element.className);
};
qq.addClass = function(element, name){
    if (!qq.hasClass(element, name)){
        element.className += ' ' + name;
    }
};
qq.removeClass = function(element, name){
    var re = new RegExp('(^| )' + name + '( |$)');
    element.className = element.className.replace(re, ' ').replace(/^\s+|\s+$/g, "");
};
qq.setText = function(element, text){
    element.innerText = text;
    element.textContent = text;
};

//
// Selecting elements

qq.children = function(element){
    var children = [],
    child = element.firstChild;

    while (child){
        if (child.nodeType == 1){
            children.push(child);
        }
        child = child.nextSibling;
    }

    return children;
};

qq.getByClass = function(element, className){
    if (element.querySelectorAll){
        return element.querySelectorAll('.' + className);
    }

    var result = [];
    var candidates = element.getElementsByTagName("*");
    var len = candidates.length;

    for (var i = 0; i < len; i++){
        if (qq.hasClass(candidates[i], className)){
            result.push(candidates[i]);
        }
    }
    return result;
};

/**
 * obj2url() takes a json-object as argument and generates
 * a querystring. pretty much like jQuery.param()
 * 
 * how to use:
 *
 *    `qq.obj2url({a:'b',c:'d'},'http://any.url/upload?otherParam=value');`
 *
 * will result in:
 *
 *    `http://any.url/upload?otherParam=value&a=b&c=d`
 *
 * @param  Object JSON-Object
 * @param  String current querystring-part
 * @return String encoded querystring
 */
qq.obj2url = function(obj, temp, prefixDone){
    var uristrings = [],
        prefix = '&',
        add = function(nextObj, i){
            var nextTemp = temp 
                ? (/\[\]$/.test(temp)) // prevent double-encoding
                   ? temp
                   : temp+'['+i+']'
                : i;
            if ((nextTemp != 'undefined') && (i != 'undefined')) {  
                uristrings.push(
                    (typeof nextObj === 'object') 
                        ? qq.obj2url(nextObj, nextTemp, true)
                        : (Object.prototype.toString.call(nextObj) === '[object Function]')
                            ? encodeURIComponent(nextTemp) + '=' + encodeURIComponent(nextObj())
                            : encodeURIComponent(nextTemp) + '=' + encodeURIComponent(nextObj)                                                          
                );
            }
        }; 

    if (!prefixDone && temp) {
      prefix = (/\?/.test(temp)) ? (/\?$/.test(temp)) ? '' : '&' : '?';
      uristrings.push(temp);
      uristrings.push(qq.obj2url(obj));
    } else if ((Object.prototype.toString.call(obj) === '[object Array]') && (typeof obj != 'undefined') ) {
        // we wont use a for-in-loop on an array (performance)
        for (var i = 0, len = obj.length; i < len; ++i){
            add(obj[i], i);
        }
    } else if ((typeof obj != 'undefined') && (obj !== null) && (typeof obj === "object")){
        // for anything else but a scalar, we will use for-in-loop
        for (var i in obj){
            add(obj[i], i);
        }
    } else {
        uristrings.push(encodeURIComponent(temp) + '=' + encodeURIComponent(obj));
    }

    return uristrings.join(prefix)
                     .replace(/^&/, '')
                     .replace(/%20/g, '+'); 
};

//
//
// Uploader Classes
//
//

var qq = qq || {};
    
/**
 * Creates upload button, validates upload, but doesn't create file list or dd. 
 */
qq.FileUploaderBasic = function(o){
    this._options = {
        // set to true to see the server response
        debug: false,
        action: '/server/upload',
        params: {},
        button: null,
        multiple: true,
        maxConnections: 3,
        // validation        
        allowedExtensions: [],               
        sizeLimit: 0,   
        minSizeLimit: 0,                             
        // events
        // return false to cancel submit
        onSubmit: function(id, fileName){},
        onProgress: function(id, fileName, loaded, total){},
        onComplete: function(id, fileName, responseJSON){},
        onCancel: function(id, fileName){},
        // messages                
        messages: {
            typeError: "{file} has invalid extension. Only {extensions} are allowed.",
            sizeError: "{file} is too large, maximum file size is {sizeLimit}.",
            minSizeError: "{file} is too small, minimum file size is {minSizeLimit}.",
            emptyError: "{file} is empty, please select files again without it.",
            onLeave: "The files are being uploaded, if you leave now the upload will be cancelled."            
        },
        showMessage: function(message){}           
    };
    qq.extend(this._options, o);
        
    // number of files being uploaded
    this._filesInProgress = 0;
    this._handler = this._createUploadHandler(); 
    
    if (this._options.button){ 
        this._button = this._createUploadButton(this._options.button);
    }
                        
    this._preventLeaveInProgress();         
};
   
qq.FileUploaderBasic.prototype = {
    setParams: function(params){
        this._options.params = params;
    },
    getInProgress: function(){
        return this._filesInProgress;         
    },
    _createUploadButton: function(element){
        var self = this;
        
        return new qq.UploadButton({
            element: element,
            multiple: this._options.multiple && qq.UploadHandlerXhr.isSupported(),
            onChange: function(input){
                self._onInputChange(input);
            }        
        });           
    },    
    _createUploadHandler: function(){
        var self = this,
            handlerClass;        
        
        if(qq.UploadHandlerXhr.isSupported()){           
            handlerClass = 'UploadHandlerXhr';                        
        } else {
            handlerClass = 'UploadHandlerForm';
        }

        var handler = new qq[handlerClass]({
            debug: this._options.debug,
            action: this._options.action,         
            maxConnections: this._options.maxConnections,   
            onProgress: function(id, fileName, loaded, total){                
                self._onProgress(id, fileName, loaded, total);
                self._options.onProgress(id, fileName, loaded, total);                    
            },            
            onComplete: function(id, fileName, result){
                self._onComplete(id, fileName, result);
                self._options.onComplete(id, fileName, result);
            },
            onCancel: function(id, fileName){
                self._onCancel(id, fileName);
                self._options.onCancel(id, fileName);
            }
        });

        return handler;
    },    
    _preventLeaveInProgress: function(){
        var self = this;
        
        qq.attach(window, 'beforeunload', function(e){
            if (!self._filesInProgress){return;}
            
            var e = e || window.event;
            // for ie, ff
            e.returnValue = self._options.messages.onLeave;
            // for webkit
            return self._options.messages.onLeave;             
        });        
    },    
    _onSubmit: function(id, fileName){
        this._filesInProgress++;  
    },
    _onProgress: function(id, fileName, loaded, total){        
    },
    _onComplete: function(id, fileName, result){
        this._filesInProgress--;                 
        if (result.error){
            this._options.showMessage(result.error);
        }             
    },
    _onCancel: function(id, fileName){
        this._filesInProgress--;        
    },
    _onInputChange: function(input){
        if (this._handler instanceof qq.UploadHandlerXhr){                
            this._uploadFileList(input.files);                   
        } else {             
            if (this._validateFile(input)){                
                this._uploadFile(input);                                    
            }                      
        }               
        this._button.reset();   
    },  
    _uploadFileList: function(files){
        for (var i=0; i<files.length; i++){
            if ( !this._validateFile(files[i])){
                return;
            }            
        }
        
        for (var i=0; i<files.length; i++){
            this._uploadFile(files[i]);        
        }        
    },       
    _uploadFile: function(fileContainer){      
        var id = this._handler.add(fileContainer);
        var fileName = this._handler.getName(id);
        
        if (this._options.onSubmit(id, fileName) !== false){
            this._onSubmit(id, fileName);
            this._handler.upload(id, this._options.params);
        }
    },      
    _validateFile: function(file){
        var name, size;
        
        if (file.value){
            // it is a file input            
            // get input value and remove path to normalize
            name = file.value.replace(/.*(\/|\\)/, "");
        } else {
            // fix missing properties in Safari
            name = file.fileName != null ? file.fileName : file.name;
            size = file.fileSize != null ? file.fileSize : file.size;
        }
                    
        if (! this._isAllowedExtension(name)){            
            this._error('typeError', name);
            return false;
            
        } else if (size === 0){            
            this._error('emptyError', name);
            return false;
                                                     
        } else if (size && this._options.sizeLimit && size > this._options.sizeLimit){            
            this._error('sizeError', name);
            return false;
                        
        } else if (size && size < this._options.minSizeLimit){
            this._error('minSizeError', name);
            return false;            
        }
        
        return true;                
    },
    _error: function(code, fileName){
        var message = this._options.messages[code];        
        function r(name, replacement){ message = message.replace(name, replacement); }
        
        r('{file}', this._formatFileName(fileName));        
        r('{extensions}', this._options.allowedExtensions.join(', '));
        r('{sizeLimit}', this._formatSize(this._options.sizeLimit));
        r('{minSizeLimit}', this._formatSize(this._options.minSizeLimit));
        
        this._options.showMessage(message);                
    },
    _formatFileName: function(name){
        if (name.length > 33){
            name = name.slice(0, 19) + '...' + name.slice(-13);    
        }
        return name;
    },
    _isAllowedExtension: function(fileName){
        var ext = (-1 !== fileName.indexOf('.')) ? fileName.replace(/.*[.]/, '').toLowerCase() : '';
        var allowed = this._options.allowedExtensions;
        
        if (!allowed.length){return true;}        
        
        for (var i=0; i<allowed.length; i++){
            if (allowed[i].toLowerCase() == ext){ return true;}    
        }
        
        return false;
    },    
    _formatSize: function(bytes){
        var i = -1;                                    
        do {
            bytes = bytes / 1024;
            i++;  
        } while (bytes > 99);
        
        return Math.max(bytes, 0.1).toFixed(1) + ['kB', 'MB', 'GB', 'TB', 'PB', 'EB'][i];          
    }
};
    
       
/**
 * Class that creates upload widget with drag-and-drop and file list
 * @inherits qq.FileUploaderBasic
 */
qq.FileUploader = function(o){
    // call parent constructor
    qq.FileUploaderBasic.apply(this, arguments);
    
    // additional options    
    qq.extend(this._options, {
        element: null,
        // if set, will be used instead of qq-upload-list in template
        listElement: null,
                
        template: '<div class="qq-uploader">' + 
                '<div class="qq-upload-drop-area hidden"></div>' +
                '<div class="qq-upload-button upload_button clickable"></div>' +
                '<ul class="qq-upload-list hidden"></ul>' + 
             '</div>',

        // template for one item in file list
        fileTemplate: '<li>' +
                '<span class="qq-upload-file hidden"></span>' +
                '<span class="qq-upload-spinner hidden"></span>' +
                '<span class="qq-upload-size hidden"></span>' +
                '<a class="qq-upload-cancel hidden" href="#">Cancel</a>' +
                '<span class="qq-upload-failed-text hidden">Failed</span>' +
            '</li>',        
        
        classes: {
            // used to get elements from templates
            button: 'qq-upload-button',
            drop: 'qq-upload-drop-area',
            dropActive: 'qq-upload-drop-area-active',
            list: 'qq-upload-list',
                        
            file: 'qq-upload-file',
            spinner: 'qq-upload-spinner',
            size: 'qq-upload-size',
            cancel: 'qq-upload-cancel',

            // added to list item when upload completes
            // used in css to hide progress spinner
            success: 'qq-upload-success',
            fail: 'qq-upload-fail'
        }
    });
    // overwrite options with user supplied    
    qq.extend(this._options, o);       

    this._element = this._options.element;
    this._element.innerHTML = this._options.template;        
    this._listElement = this._options.listElement || this._find(this._element, 'list');
    
    this._classes = this._options.classes;
        
    this._button = this._createUploadButton(this._find(this._element, 'button'));        
    
    this._bindCancelEvent();
    this._setupDragDrop();
};

// inherit from Basic Uploader
qq.extend(qq.FileUploader.prototype, qq.FileUploaderBasic.prototype);

qq.extend(qq.FileUploader.prototype, {
    /**
     * Gets one of the elements listed in this._options.classes
     **/
    _find: function(parent, type){                                
        var element = qq.getByClass(parent, this._options.classes[type])[0];        
        if (!element){
            throw new Error('element not found ' + type);
        }
        
        return element;
    },
    _setupDragDrop: function(){
        var self = this,
            dropArea = this._find(this._element, 'drop');                        

        var dz = new qq.UploadDropZone({
            element: dropArea,
            onEnter: function(e){
                qq.addClass(dropArea, self._classes.dropActive);
                e.stopPropagation();
            },
            onLeave: function(e){
                e.stopPropagation();
            },
            onLeaveNotDescendants: function(e){
                qq.removeClass(dropArea, self._classes.dropActive);  
            },
            onDrop: function(e){
                dropArea.style.display = 'none';
                qq.removeClass(dropArea, self._classes.dropActive);
                self._uploadFileList(e.dataTransfer.files);    
            }
        });
                
        dropArea.style.display = 'none';

        qq.attach(document, 'dragenter', function(e){     
            if (!dz._isValidFileDrag(e)) return; 
            
            dropArea.style.display = 'block';            
        });                 
        qq.attach(document, 'dragleave', function(e){
            if (!dz._isValidFileDrag(e)) return;            
            
            var relatedTarget = document.elementFromPoint(e.clientX, e.clientY);
            // only fire when leaving document out
            if ( ! relatedTarget || relatedTarget.nodeName == "HTML"){               
                dropArea.style.display = 'none';                                            
            }
        });                
    },
    _onSubmit: function(id, fileName){
        qq.FileUploaderBasic.prototype._onSubmit.apply(this, arguments);
        this._addToList(id, fileName);  
    },
    _onProgress: function(id, fileName, loaded, total){
        qq.FileUploaderBasic.prototype._onProgress.apply(this, arguments);

        var item = this._getItemByFileId(id);
        var size = this._find(item, 'size');
        size.style.display = 'inline';
        
        var text; 
        if (loaded != total){
            text = Math.round(loaded / total * 100) + '% from ' + this._formatSize(total);
        } else {                                   
            text = this._formatSize(total);
        }          
        
        qq.setText(size, text);         
    },
    _onComplete: function(id, fileName, result){
        qq.FileUploaderBasic.prototype._onComplete.apply(this, arguments);

        // mark completed
        var item = this._getItemByFileId(id);                
        qq.remove(this._find(item, 'cancel'));
        qq.remove(this._find(item, 'spinner'));
        
        if (result.success){
            qq.addClass(item, this._classes.success);    
        } else {
            qq.addClass(item, this._classes.fail);
        }         
    },
    _addToList: function(id, fileName){
        var item = qq.toElement(this._options.fileTemplate);                
        item.qqFileId = id;

        var fileElement = this._find(item, 'file');        
        qq.setText(fileElement, this._formatFileName(fileName));
        this._find(item, 'size').style.display = 'none';        

        this._listElement.appendChild(item);
    },
    _getItemByFileId: function(id){
        var item = this._listElement.firstChild;        
        
        // there can't be txt nodes in dynamically created list
        // and we can  use nextSibling
        while (item){            
            if (item.qqFileId == id) return item;            
            item = item.nextSibling;
        }          
    },
    /**
     * delegate click event for cancel link 
     **/
    _bindCancelEvent: function(){
        var self = this,
            list = this._listElement;            
        
        qq.attach(list, 'click', function(e){            
            e = e || window.event;
            var target = e.target || e.srcElement;
            
            if (qq.hasClass(target, self._classes.cancel)){                
                qq.preventDefault(e);
               
                var item = target.parentNode;
                self._handler.cancel(item.qqFileId);
                qq.remove(item);
            }
        });
    }    
});
    
qq.UploadDropZone = function(o){
    this._options = {
        element: null,  
        onEnter: function(e){},
        onLeave: function(e){},  
        // is not fired when leaving element by hovering descendants   
        onLeaveNotDescendants: function(e){},   
        onDrop: function(e){}                       
    };
    qq.extend(this._options, o); 
    
    this._element = this._options.element;
    
    this._disableDropOutside();
    this._attachEvents();   
};

qq.UploadDropZone.prototype = {
    _disableDropOutside: function(e){
        // run only once for all instances
        if (!qq.UploadDropZone.dropOutsideDisabled ){

            qq.attach(document, 'dragover', function(e){
                if (e.dataTransfer){
                    e.dataTransfer.dropEffect = 'none';
                    e.preventDefault(); 
                }           
            });
            
            qq.UploadDropZone.dropOutsideDisabled = true; 
        }        
    },
    _attachEvents: function(){
        var self = this;              
                  
        qq.attach(self._element, 'dragover', function(e){
            if (!self._isValidFileDrag(e)) return;
            
            var effect = e.dataTransfer.effectAllowed;
            if (effect == 'move' || effect == 'linkMove'){
                e.dataTransfer.dropEffect = 'move'; // for FF (only move allowed)    
            } else {                    
                e.dataTransfer.dropEffect = 'copy'; // for Chrome
            }
                                                     
            e.stopPropagation();
            e.preventDefault();                                                                    
        });
        
        qq.attach(self._element, 'dragenter', function(e){
            if (!self._isValidFileDrag(e)) return;
                        
            self._options.onEnter(e);
        });
        
        qq.attach(self._element, 'dragleave', function(e){
            if (!self._isValidFileDrag(e)) return;
            
            self._options.onLeave(e);
            
            var relatedTarget = document.elementFromPoint(e.clientX, e.clientY);                      
            // do not fire when moving a mouse over a descendant
            if (qq.contains(this, relatedTarget)) return;
                        
            self._options.onLeaveNotDescendants(e); 
        });
                
        qq.attach(self._element, 'drop', function(e){
            if (!self._isValidFileDrag(e)) return;
            
            e.preventDefault();
            self._options.onDrop(e);
        });          
    },
    _isValidFileDrag: function(e){
        var dt = e.dataTransfer,
            // do not check dt.types.contains in webkit, because it crashes safari 4            
            isWebkit = navigator.userAgent.indexOf("AppleWebKit") > -1;                        

        // dt.effectAllowed is none in Safari 5
        // dt.types.contains check is for firefox            
        return dt && dt.effectAllowed != 'none' && 
            (dt.files || (!isWebkit && dt.types.contains && dt.types.contains('Files')));
        
    }        
}; 

qq.UploadButton = function(o){
    this._options = {
        element: null,  
        // if set to true adds multiple attribute to file input      
        multiple: false,
        // name attribute of file input
        name: 'file',
        onChange: function(input){},
        hoverClass: 'qq-upload-button-hover',
        focusClass: 'qq-upload-button-focus'                       
    };
    
    qq.extend(this._options, o);
        
    this._element = this._options.element;
    
    // make button suitable container for input
    qq.css(this._element, {
        position: 'relative',
        overflow: 'hidden',
        // Make sure browse button is in the right side
        // in Internet Explorer
        direction: 'ltr'
    });   
    
    this._input = this._createInput();
};

qq.UploadButton.prototype = {
    /* returns file input element */    
    getInput: function(){
        return this._input;
    },
    /* cleans/recreates the file input */
    reset: function(){
        if (this._input.parentNode){
            qq.remove(this._input);    
        }                
        
        qq.removeClass(this._element, this._options.focusClass);
        this._input = this._createInput();
    },    
    _createInput: function(){                
        var input = document.createElement("input");
        
        if (this._options.multiple){
            input.setAttribute("multiple", "multiple");
        }
                
        input.setAttribute("type", "file");
        input.setAttribute("name", this._options.name);
        
        qq.css(input, {
            position: 'absolute',
            // in Opera only 'browse' button
            // is clickable and it is located at
            // the right side of the input
            right: 0,
            top: 0,
            fontFamily: 'Arial',
            // 4 persons reported this, the max values that worked for them were 243, 236, 236, 118
            fontSize: '118px',
            margin: 0,
            padding: 0,
            cursor: 'pointer',
            opacity: 0
        });
        
        this._element.appendChild(input);

        var self = this;
        qq.attach(input, 'change', function(){
            self._options.onChange(input);
        });
                
        qq.attach(input, 'mouseover', function(){
            qq.addClass(self._element, self._options.hoverClass);
        });
        qq.attach(input, 'mouseout', function(){
            qq.removeClass(self._element, self._options.hoverClass);
        });
        qq.attach(input, 'focus', function(){
            qq.addClass(self._element, self._options.focusClass);
        });
        qq.attach(input, 'blur', function(){
            qq.removeClass(self._element, self._options.focusClass);
        });

        // IE and Opera, unfortunately have 2 tab stops on file input
        // which is unacceptable in our case, disable keyboard access
        if (window.attachEvent){
            // it is IE or Opera
            input.setAttribute('tabIndex', "-1");
        }

        return input;            
    }        
};

/**
 * Class for uploading files, uploading itself is handled by child classes
 */
qq.UploadHandlerAbstract = function(o){
    this._options = {
        debug: false,
        action: '/upload.php',
        // maximum number of concurrent uploads        
        maxConnections: 999,
        onProgress: function(id, fileName, loaded, total){},
        onComplete: function(id, fileName, response){},
        onCancel: function(id, fileName){}
    };
    qq.extend(this._options, o);    
    
    this._queue = [];
    // params for files in queue
    this._params = [];
};
qq.UploadHandlerAbstract.prototype = {
    log: function(str){
        if (this._options.debug && window.console) console.log('[uploader] ' + str);        
    },
    /**
     * Adds file or file input to the queue
     * @returns id
     **/    
    add: function(file){},
    /**
     * Sends the file identified by id and additional query params to the server
     */
    upload: function(id, params){
        var len = this._queue.push(id);

        var copy = {};        
        qq.extend(copy, params);
        this._params[id] = copy;        
                
        // if too many active uploads, wait...
        if (len <= this._options.maxConnections){               
            this._upload(id, this._params[id]);
        }
    },
    /**
     * Cancels file upload by id
     */
    cancel: function(id){
        this._cancel(id);
        this._dequeue(id);
    },
    /**
     * Cancells all uploads
     */
    cancelAll: function(){
        for (var i=0; i<this._queue.length; i++){
            this._cancel(this._queue[i]);
        }
        this._queue = [];
    },
    /**
     * Returns name of the file identified by id
     */
    getName: function(id){},
    /**
     * Returns size of the file identified by id
     */          
    getSize: function(id){},
    /**
     * Returns id of files being uploaded or
     * waiting for their turn
     */
    getQueue: function(){
        return this._queue;
    },
    /**
     * Actual upload method
     */
    _upload: function(id){},
    /**
     * Actual cancel method
     */
    _cancel: function(id){},     
    /**
     * Removes element from queue, starts upload of next
     */
    _dequeue: function(id){
        var i = qq.indexOf(this._queue, id);
        this._queue.splice(i, 1);
                
        var max = this._options.maxConnections;
        
        if (this._queue.length >= max){
            var nextId = this._queue[max-1];
            this._upload(nextId, this._params[nextId]);
        }
    }        
};

/**
 * Class for uploading files using form and iframe
 * @inherits qq.UploadHandlerAbstract
 */
qq.UploadHandlerForm = function(o){
    qq.UploadHandlerAbstract.apply(this, arguments);
       
    this._inputs = {};
};
// @inherits qq.UploadHandlerAbstract
qq.extend(qq.UploadHandlerForm.prototype, qq.UploadHandlerAbstract.prototype);

qq.extend(qq.UploadHandlerForm.prototype, {
    add: function(fileInput){
        fileInput.setAttribute('name', 'qqfile');
        var id = 'qq-upload-handler-iframe' + qq.getUniqueId();       
        
        this._inputs[id] = fileInput;
        
        // remove file input from DOM
        if (fileInput.parentNode){
            qq.remove(fileInput);
        }
                
        return id;
    },
    getName: function(id){
        // get input value and remove path to normalize
        return this._inputs[id].value.replace(/.*(\/|\\)/, "");
    },    
    _cancel: function(id){
        this._options.onCancel(id, this.getName(id));
        
        delete this._inputs[id];        

        var iframe = document.getElementById(id);
        if (iframe){
            // to cancel request set src to something else
            // we use src="javascript:false;" because it doesn't
            // trigger ie6 prompt on https
            iframe.setAttribute('src', 'javascript:false;');

            qq.remove(iframe);
        }
    },     
    _upload: function(id, params){                        
        var input = this._inputs[id];
        
        if (!input){
            throw new Error('file with passed id was not added, or already uploaded or cancelled');
        }                

        var fileName = this.getName(id);
                
        var iframe = this._createIframe(id);
        var form = this._createForm(iframe, params);
        form.appendChild(input);

        var self = this;
        this._attachLoadEvent(iframe, function(){                                 
            self.log('iframe loaded');
            
            var response = self._getIframeContentJSON(iframe);

            self._options.onComplete(id, fileName, response);
            self._dequeue(id);
            
            delete self._inputs[id];
            // timeout added to fix busy state in FF3.6
            setTimeout(function(){
                qq.remove(iframe);
            }, 1);
        });

        form.submit();        
        qq.remove(form);        
        
        return id;
    }, 
    _attachLoadEvent: function(iframe, callback){
        qq.attach(iframe, 'load', function(){
            // when we remove iframe from dom
            // the request stops, but in IE load
            // event fires
            if (!iframe.parentNode){
                return;
            }

            // fixing Opera 10.53
            if (iframe.contentDocument &&
                iframe.contentDocument.body &&
                iframe.contentDocument.body.innerHTML == "false"){
                // In Opera event is fired second time
                // when body.innerHTML changed from false
                // to server response approx. after 1 sec
                // when we upload file with iframe
                return;
            }

            callback();
        });
    },
    /**
     * Returns json object received by iframe from server.
     */
    _getIframeContentJSON: function(iframe){
        // iframe.contentWindow.document - for IE<7
        var doc = iframe.contentDocument ? iframe.contentDocument: iframe.contentWindow.document,
            response;
        
        this.log("converting iframe's innerHTML to JSON");
        this.log("innerHTML = " + doc.body.innerHTML);
                        
        try {
            response = eval("(" + doc.body.innerHTML + ")");
        } catch(err){
            response = {};
        }        

        return response;
    },
    /**
     * Creates iframe with unique name
     */
    _createIframe: function(id){
        // We can't use following code as the name attribute
        // won't be properly registered in IE6, and new window
        // on form submit will open
        // var iframe = document.createElement('iframe');
        // iframe.setAttribute('name', id);

        var iframe = qq.toElement('<iframe src="javascript:false;" name="' + id + '" />');
        // src="javascript:false;" removes ie6 prompt on https

        iframe.setAttribute('id', id);

        iframe.style.display = 'none';
        document.body.appendChild(iframe);

        return iframe;
    },
    /**
     * Creates form, that will be submitted to iframe
     */
    _createForm: function(iframe, params){
        // We can't use the following code in IE6
        // var form = document.createElement('form');
        // form.setAttribute('method', 'post');
        // form.setAttribute('enctype', 'multipart/form-data');
        // Because in this case file won't be attached to request
        var form = qq.toElement('<form method="post" enctype="multipart/form-data"></form>');

        var queryString = qq.obj2url(params, this._options.action);

        form.setAttribute('action', queryString);
        form.setAttribute('target', iframe.name);
        form.style.display = 'none';
        document.body.appendChild(form);

        return form;
    }
});

/**
 * Class for uploading files using xhr
 * @inherits qq.UploadHandlerAbstract
 */
qq.UploadHandlerXhr = function(o){
    qq.UploadHandlerAbstract.apply(this, arguments);

    this._files = [];
    this._xhrs = [];
    
    // current loaded size in bytes for each file 
    this._loaded = [];
};

// static method
qq.UploadHandlerXhr.isSupported = function(){
    var input = document.createElement('input');
    input.type = 'file';        
    
    return (
        'multiple' in input &&
        typeof File != "undefined" &&
        typeof (new XMLHttpRequest()).upload != "undefined" );       
};

// @inherits qq.UploadHandlerAbstract
qq.extend(qq.UploadHandlerXhr.prototype, qq.UploadHandlerAbstract.prototype)

qq.extend(qq.UploadHandlerXhr.prototype, {
    /**
     * Adds file to the queue
     * Returns id to use with upload, cancel
     **/    
    add: function(file){
        if (!(file instanceof File)){
            throw new Error('Passed obj in not a File (in qq.UploadHandlerXhr)');
        }
                
        return this._files.push(file) - 1;        
    },
    getName: function(id){        
        var file = this._files[id];
        // fix missing name in Safari 4
        return file.fileName != null ? file.fileName : file.name;       
    },
    getSize: function(id){
        var file = this._files[id];
        return file.fileSize != null ? file.fileSize : file.size;
    },    
    /**
     * Returns uploaded bytes for file identified by id 
     */    
    getLoaded: function(id){
        return this._loaded[id] || 0; 
    },
    /**
     * Sends the file identified by id and additional query params to the server
     * @param {Object} params name-value string pairs
     */    
    _upload: function(id, params){
        var file = this._files[id],
            name = this.getName(id),
            size = this.getSize(id);
                
        this._loaded[id] = 0;
                                
        var xhr = this._xhrs[id] = new XMLHttpRequest();
        var self = this;
                                        
        xhr.upload.onprogress = function(e){
            if (e.lengthComputable){
                self._loaded[id] = e.loaded;
                self._options.onProgress(id, name, e.loaded, e.total);
            }
        };

        xhr.onreadystatechange = function(){            
            if (xhr.readyState == 4){
                self._onComplete(id, xhr);                    
            }
        };

        // build query string
        params = params || {};
        params['qqfile'] = name;
        var queryString = qq.obj2url(params, this._options.action);

        xhr.open("POST", queryString, true);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.setRequestHeader("X-File-Name", encodeURIComponent(name));
        xhr.setRequestHeader("Content-Type", "application/octet-stream");
        xhr.send(file);
    },
    _onComplete: function(id, xhr){
        // the request was aborted/cancelled
        if (!this._files[id]) return;
        
        var name = this.getName(id);
        var size = this.getSize(id);
        
        this._options.onProgress(id, name, size, size);
                
        if (xhr.status == 200){
            this.log("xhr - server response received");
            this.log("responseText = " + xhr.responseText);
                        
            var response;
                    
            try {
                response = eval("(" + xhr.responseText + ")");
            } catch(err){
                response = {};
            }
            
            this._options.onComplete(id, name, response);
                        
        } else {                   
            this._options.onComplete(id, name, {});
        }
                
        this._files[id] = null;
        this._xhrs[id] = null;    
        this._dequeue(id);                    
    },
    _cancel: function(id){
        this._options.onCancel(id, this.getName(id));
        
        this._files[id] = null;
        
        if (this._xhrs[id]){
            this._xhrs[id].abort();
            this._xhrs[id] = null;                                   
        }
    }
});

$(".type_switcher").live("change", function(){
	$(".switched").hide();
	$(".switched_"+$(this).val()).show();
});

$("#transparent td img.cancel").live("click",function(){  
	$(this).parent().remove();
});

window.processing_image = 0;
window.processing_file = 0;

$('#addform').die("submit").live("submit", function() {
	unregister_unload();
});

$.tools.dateinput.localize("ru", {
	'months': 'Январь,Февраль,Март,Апрель,Май,Июнь,Июль,Август,Сентябрь,Октябрь,Ноябрь,Декабрь',
	'shortMonths': 'янв,фев,март,апр,май,июнь,июль,авг,сен,окт,ноя,дек',
	'days': 'понедельник,вторник,среда,четверг,пятница,суббота,воскресенье',
	'shortDays': 'пнд,вт,ср,чтв,птн,суб,вск'
});

$("input[type=date]").dateinput({
	'lang': 'ru',
	'format': 'mmmm dd, yyyy'
});

$('.wysiwyg').wysiwyg({
	'controls': {
		'bold': { visible: true, tooltip: "Выделить жирным" },
		'italic': { visible: true, tooltip: "Выделить курсивом" },
		'strikeThrough': { visible: true, tooltip: "Перечеркнуть текст" },
		'underline': { visible: true, tooltip: "Подчеркнуть текст" },
		'justifyLeft': { visible: true, tooltip: "Выровнять по левому краю" },
		'justifyCenter': { visible: true, tooltip: "Выровнять по центру" },
		'justifyRight': { visible: true, tooltip: "Выровнять по правому краю" },
		'justifyFull': { visible: true, tooltip: "Растянуть текст по ширине поля" },
		'code': { visible: true, tooltip: "Моноширнный текст" },
		'indent': { visible: true, tooltip: "Добавить отступ" },
		'outdent': { visible: true, tooltip: "Убрать отступ" },
		'subscript': { visible: true, tooltip: "Нижний индекс" },
		'superscript': { visible: true, tooltip: "Верхний индекс" },
		'undo': { visible: true, tooltip: "Отменить действие" },
		'redo': { visible: true, tooltip: "Вернуть действие" },
		'insertOrderedList': { visible: true, tooltip: "Добавить нумерованный список" },
		'insertUnorderedList': { visible: true, tooltip: "Добавить ненумерованный список" },
		'insertHorizontalRule': { visible: true, tooltip: "Горизонтальная черта" },
		'createLink': { visible: true, tooltip: "Добавить ссылку" },
		'insertImage': { visible: false },
		'h1': { visible: false },
		'h2': { visible: false },
		'h3': { visible: false },
		'paragraph': { visible: false },
		'cut': { visible: false },
		'copy': { visible: false },
		'paste': { visible: false },
		'increaseFontSize': { visible: false },
		'decreaseFontSize': { visible: false },
		'html': { visible: false },
		'removeFormat': { visible: true, tooltip: "Отменить форматирование" },
		'insertTable': { visible: false },
		'spoiler': {
			visible : true,
			groupIndex: 6,
			tooltip: "Вставить разворачивающий спойлер",
			exec: function () {
				var range	= this.getInternalRange();

				var spoiler_handler = prompt("Задайте заголовок спойлера");
				
				if (spoiler_handler != null && spoiler_handler != "") {
				
					var spoiler = this.editorDoc.createElement('div');
					var wrapper = this.editorDoc.createElement('div');
					var handler = this.editorDoc.createElement('div');
					
					$(spoiler).addClass("mini-shell");
					$(wrapper).addClass("text").addClass("hidden");
					$(handler).addClass("handler").html(
						'<span class="sign">↓</span> <a href="#" class="disabled">'+
						spoiler_handler
						+'</a>');

					range.surroundContents(wrapper);
					range.insertNode(handler);
					range.surroundContents(spoiler);
				}
			}
		}
	},
	'css': '/new/templates/css/add/jwysiwyg.css?ver=2',
	'autoGrow': true,
	'initialContent': '',
	'messages': {
		'nonSelection': "Сперва выделите текст ссылки"
	},
});

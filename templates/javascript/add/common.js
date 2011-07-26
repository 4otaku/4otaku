$(".type_switcher").live("change", function(){
	$(".switched").hide();
	$(".switched_"+$(this).val()).show();
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
		'bold': { visible: true },
		'italic': { visible: true },
		'strikeThrough': { visible: true },
		'underline': { visible: true },
		'justifyLeft': { visible: true },
		'justifyCenter': { visible: true },
		'justifyRight': { visible: true },
		'justifyFull': { visible: true },
		'code': { visible: true },
		'indent': { visible: true },
		'outdent': { visible: true },
		'subscript': { visible: true },
		'superscript': { visible: true },
		'undo': { visible: true },
		'redo': { visible: true },
		'insertOrderedList': { visible: true },
		'insertUnorderedList': { visible: true },
		'insertHorizontalRule': { visible: true },
		'createLink': { visible: true },
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
		'removeFormat': { visible: true },
		'insertTable': { visible: false }
	},
	'css': '/new/templates/css/add/jwysiwyg.css',
	'autoGrow': true,
	'initialContent': '',
	'messages': {
		'nonSelection': "Сперва выделите текст ссылки"
	},
});

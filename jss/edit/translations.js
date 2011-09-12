function initialize_translations() {
	window.onbeforeunload = function (e) {
		var message = 'Вы точно хотите уйти с этой страницы? Если вы уйдете, то потеряете прогресс сделанный в переводе.';
		if (typeof e == 'undefined') e = window.event;
		if (e) e.returnValue = message;
		return message;
	}	
	
	$(".art_translation_active").unbind('draggable').draggable({containment: 'parent'});
	$(".art_translation_active").unbind('resizable').resizable({
		containment: 'parent', 
		minHeight: 20, 
		minWidth: 20, 
		resize: function(event, ui) { 
			$(".ui-resizable-resizing .edit_switch").css('top',(ui.size.height/2 - 8));
			$(".ui-resizable-resizing .edit_switch").css('left',(ui.size.width/2 - 8));					
		},
		stop: function(event, ui) { 
			$(".ui-resizable-resizing .edit_switch").css('top',(ui.size.height/2 - 8));
			$(".ui-resizable-resizing .edit_switch").css('left',(ui.size.width/2 - 8));					
		}
	});
	$("img.cancel").unbind('click').click(function(){ $(this).parent().remove(); }); 
	$(".edit_switch").unbind('click').click(function(){
		if ($(this).attr('rel') != 'on') {
			$(this).attr('rel','on');
			$(this).parent().append(
				'<div class="edit_translation" style="top:'+$(this).parent().height()+'px;">'+
					'<textarea>'+
						$(this).attr('title')+
					'</textarea>'+
				'</div>'
				);
		}
		else {
			$(this).attr('title',$(this).parent().find('textarea').val().replace( /"/g, '&quot;'));
			$(this).attr('rel','off');
			$(this).parent().children('.edit_translation').remove();
		}
	});
}

function get_data() {
	window.onbeforeunload = null;
	
	$(".translation_author").appendTo(".booru_main form");
	$(".booru_main div.image").appendTo(".booru_main form");
	window.count = 0;
	$(".art_translation_active").each(function(){
		window.count = window.count + 1;
		$(this).append(
			'<input type="hidden" name="trans['+window.count+'][x1]" value="'+$(this).css('left')+'">'+
			'<input type="hidden" name="trans['+window.count+'][y1]" value="'+$(this).css('top')+'">'+
			'<input type="hidden" name="trans['+window.count+'][x2]" value="'+$(this).width()+'">'+
			'<input type="hidden" name="trans['+window.count+'][y2]" value="'+$(this).height()+'">'+
			'<input type="hidden" name="trans['+window.count+'][text]" value="'+$(this).children('.edit_switch').attr('title')+'">'			
		);
	});
	$(".booru_main div.image").append(
		'<input type="hidden" name="size" value="'+$(".booru_main div.image").attr('rel')+'">'+
		'<input type="hidden" name="type" value="'+$("form#edit_post input[name='type']").val()+'">'+
		'<input type="hidden" name="id" value="'+$("form#edit_post input[name='id']").val()+'">'+
		'<input type="hidden" name="part" value="'+$("form#edit_post input[name='part']").val()+'">'
	);	
	return $(".booru_main form").serialize();
}

$(document).ready(function(){

	$('body').css('cursor','crosshair');
	$(".booru_main").append('<form></form>');
	$(".art_translation").each(function(){
		$(this).addClass('art_translation_active');
		$(this).unbind('mouseenter mouseleave');
		$(this).removeClass('art_translation');
		$(this).html(
			'<img class="cancel" src="/images/cancel.png">'+
			'<a class="edit_switch" href="#" title="'+$(this).attr('title').replace( /"/g, '&quot;')+'" style="top:'+($(this).height()/2-8)+'px;left:'+($(this).width()/2-8)+'px;">'+
				'<img class="noadd" src="/images/translation_edit.png">'+
			'</a>'
		);
		$(this).removeAttr('title');
	});
	$("#easyTooltip").remove();
	
	$(".booru_main div.image").unbind('click').click(function(e){
		if (e.target.className != 'cancel' && e.target.className != 'noadd' && e.target.tagName == 'IMG') {
			var left = e.pageX - $(this).offset().left - 40;
			var top = e.pageY - $(this).offset().top - 40;
			if (left < 0) left = 0; if (top < 0) top = 0;
			if (left > $(this).width() - 80) left = $(this).width() - 80; if (top > $(this).height() - 80) top = $(this).height() - 80;			
			$(this).append(
				'<div class="art_translation_active" style="width:80px;height:80px;top:'+top+'px;left:'+left+'px;">'+
					'<img class="cancel" src="/images/cancel.png">'+
					'<a class="edit_switch" href="#" title="" style="top:32px;left:32px;">'+
						'<img class="noadd" src="/images/translation_edit.png">'+
					'</a>'+
				'</div>'				
			);
			initialize_translations();
		}
	});
	
	initialize_translations();	
	
	$(".edit_translation textarea").live('keyup', function() {
		$(this).parent().parent().children('.edit_switch').attr('title',$(this).val().replace( /"/g, '&quot;'));		
	});
	
	$(".edit_switch").live('click', function(event) {
		event.preventDefault();
	});		
});  

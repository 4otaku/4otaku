function urlencode(str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}

$(document).ready(function(){  

	$(".edit_comment").click(function(){  
		$('.edit-'+$(this).attr('rel')).load("/ajax.php?m=edit&f=comment&id="+$(this).attr("rel"), function(){ });
	});

	$(".delete_comment").click(function(){  
		if ($('.edit-'+$(this).attr('rel')).html()) {$('.edit-'+$(this).attr('rel')).html(''); }
		else {$('.edit-'+$(this).attr('rel')).html('<br /><form method="post" enctype="multipart/form-data"><input type="hidden" name="do" value="comment.delete" /><input type="checkbox" name="sure" /><input type="hidden" name="id" value="'+$(this).attr('rel')+'" /><input type="submit" value="Удалить" class="submit" /></form>');	}
	});

	$(".search_tags").click(function(){  
		if ($(".searchtags").val().length > 0) window.location = "/admin/tags/search/"+urlencode($(".searchtags").val())+"/";
		else window.location = "/admin/tags/";
	});
	
	$("input.searchtags").keydown(function(e){
		switch (e.which) {
			case 13:
				e.preventDefault();	
				$(".search_tags").click();
				break;
			default:
				break; 
		}      		
	});	

	$(".delete_tag").click(function(){  
		if (confirm("Удалить тег "+$(this).parents('tr').find('input[name$=name]').val()+"?")) {
			$.post("/ajax.php?m=admin&f=delete_tag&id="+$(this).attr('rel').split('|')[0]+"&old_alias="+$(this).attr('rel').split('|')[1]);
			$(this).parents('#admin_tags tr').remove();
		}
	});	

	$(".save_all").click(function(){  
		$("body").append('<div id="darklayer"><div id="message">Подождите, выполняю работу.</div></div>');
		$("tr.changed form").each(function(){  
			var data_str = $(this).serialize();
			$.ajax({ type: "POST", url: "index.php", data: data_str, async: false });
		});
		window.location.reload();
	});	

	$("#admin_tags input, #admin_tags select").change(function(){  
		$(this).parents('#admin_tags tr').addClass('changed');
	});	

	$(".admin_color_tag").click(function(){  
		$.post("/ajax.php?m=admin&f=color_tag&tag="+urlencode($(this).attr('rel').split('|')[1])+"&color="+$(this).attr('rel').split('|')[0]+"&id="+$(this).attr('rel').split('|')[2], function() {
			window.location.reload();
		});
	});	

	$(".admin_drop_color_tag").click(function(){  
		if (confirm("Отменить цвет тега?")) {
			$.post("/ajax.php?m=admin&f=delete_color_tag&id="+$(this).attr('rel'), function() {
				window.location.reload();
			});
		}
	});	
});  

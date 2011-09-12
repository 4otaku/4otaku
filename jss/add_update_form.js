$(document).ready(function(){  	

    function update_main ($) {
		min = 0; max = 0;
		$(".arrow-holder").each(function(){ if (parseInt($(this).attr('rel')) < parseInt(min)) {min = $(this).attr('rel');} if (parseInt($(this).attr('rel')) > parseInt(max)) {max = $(this).attr('rel');} });
		$(".arrow-holder").each(function(){ 
			$(this).html('');
			if ($(this).attr('rel') != max) {$(this).append('<img src="/images/str2.png" class="shift-down" rel="'+$(this).attr('rel')+'">');} 
			if ($(this).attr('rel') != min) {
				if ($(this).attr('rel') == max) {$(this).append('<img src="/images/blank.gif">');}
				$(this).append('<img src="/images/str1.png" class="shift-up" rel="'+$(this).attr('rel')+'">');
			}				
		});
		$(".shift-down").click(function(){ 
			
			num = parseInt($(this).attr("rel")) + 1; first = []; second = [];
			$('.row-'+$(this).attr('rel')+' input,.row-'+$(this).attr('rel')+' select').each(function(){
				first[first.length] = $(this).val();
			});
			$('.row-'+num+' input,.row-'+num+' select').each(function(){
				second[second.length] = $(this).val();
			});
			key = 0;
			$('.row-'+$(this).attr('rel')+' input,.row-'+$(this).attr('rel')+' select').each(function(){
				$(this).val(second[key]); key++;
			});			
			key = 0;
			$('.row-'+num+' input,.row-'+num+' select').each(function(){
				$(this).val(first[key]); key++;
			});
			
		});
		$(".shift-up").click(function(){ 
			
			num = parseInt($(this).attr("rel")) - 1; first = []; second = [];
			$('.row-'+$(this).attr('rel')+' input,.row-'+$(this).attr('rel')+' select').each(function(){
				first[first.length] = $(this).val();
			});
			$('.row-'+num+' input,.row-'+num+' select').each(function(){
				second[second.length] = $(this).val();
			});
			key = 0;
			$('.row-'+$(this).attr('rel')+' input,.row-'+$(this).attr('rel')+' select').each(function(){
				$(this).val(second[key]); key++;
			});			
			key = 0;
			$('.row-'+num+' input,.row-'+num+' select').each(function(){
				$(this).val(first[key]); key++;
			});
			
		});		
    }
	
	update_main ($);


	$("input.disabled").click(function(event){  
		event.preventDefault();
	});
	
	$("a.disabled").click(function(event){  
		event.preventDefault();
	});

	$("input.remove_link").click(function(){  	
		$("#link-"+$(this).attr('rel')).remove();
		update_main ($);
	});
	
	$("input#add_link").click(function(){  
		field = '<td class="input" bgcolor="#e6e6e6" width="23%"><div align="right"><strong>Ссылка</strong></div></td><td class="inputdata" bgcolor="#efefef"><span class="row-'+$(this).attr('rel')+'"><input size="10" type="text" name="link['+$(this).attr("rel")+'][name]" value="Скачать" />: <input size="30" type="text" name="link['+$(this).attr("rel")+'][link]" value="http://" /> ~(<input size="2" type="text" name="link['+$(this).attr("rel")+'][size]" value="" /> <select name="link['+$(this).attr("rel")+'][sizetype]"><option value="кб">кб</option><option value="мб" selected>мб</option><option value="гб">гб</option></select>) &nbsp; <input type="submit" class="disabled remove_link" value="-" rel="'+$(this).attr("rel")+'" /></span><span class="arrow-holder" rel="'+$(this).attr("rel")+'"></span></td>';
		$("#main_fields tbody").append('<tr id="link-'+$(this).attr("rel")+'">'+field+'</tr>');
		a=parseInt($(this).attr("rel"));
		$(this).attr('rel',a+1);
		$("input.disabled").click(function(event){  event.preventDefault();	});
		$("input.remove_link").click(function(){ $("#link-"+$(this).attr('rel')).remove();	update_main ($); });
		update_main ($);
	});
	
}); 
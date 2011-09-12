$(document).ready(function(){

	$('.booru_images').sortable();
	$('.booru_images').disableSelection();
	$('.booru_images a').click(function(e){
		e.preventDefault();
	});

});  

$("#TB_closeWindowButton").unbind('click').click(function(e){
	e.preventDefault();
	if (window.changed == true) {
		document.location.reload();
	} else {
		tb_remove();
	}
});
$(document).unbind('keyup').keyup(function(e) {
	if (e.keyCode == 27) {
		if (window.changed == true) {
			document.location.reload();
		} else {
			tb_remove();
		}
	}
});

$(document).ready(function() {
	$("input.checked").attr('checked', 'checked');

	$(".activate_plugin").click(function(){
		var value = $(this).is(':checked') - 0;

		$.post("/ajax.php?m=cookie&f=set&field=plugins."+$(this).attr('rel')+"&val="+value);

		window.changed = true;
	});
});


function get_login_data(){
	var data = {};
	$(".login_input_table").find(".login_input").each(function(){
		data[$(this).attr('name')] = $(this).val();
	});

	return data;
}

function display_login_results(result){
	result = $.parseJSON(result);

	if (result.success) {
		document.location.reload();
	} else {
		$("#TB_ajaxContent #error").remove();
		$("#TB_ajaxContent").prepend(
			"<div id='error'>"+
			result.data +
			"</div>"
		);
	}
}

$(document).ready(function() {

	$("select .selected").attr('selected', 'selected');

	$(".login_action").unbind('click').click(function(){
		var data = get_login_data(),
			addr = $(this).attr('rel');

		$.post(window.config.site_dir+"/ajax.php?m=login&f="+addr,
			data, display_login_results);
	});
});

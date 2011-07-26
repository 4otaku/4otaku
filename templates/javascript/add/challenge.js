$(".fighting_character_needed").live("change", function(){
	var field_needed = false;

	$(".fighting_character_needed").each(function(){
		if ($(this).is(":checkbox") && $(this).is(":checked")) {
			field_needed = true;
		}

		if ($(this).is("select") && $(this).val() > 0) {
			field_needed = true;
		}
	})

	if (field_needed) {
		$(".fighting_character_list").slideDown();
	} else {
		$(".fighting_character_list").slideUp();
	}
});

$(function() {
	$('.sortable').sortable({
		containment: '.sortable-holder',
		handle: '.handler',
		forceHelperSize: true,
		forcePlaceholderSize: true,
		start: function(event, ui) {
			ui.item.find('.remove_link').css('visibility', 'hidden');
		},
		stop: function(event, ui) {
			ui.item.find('.remove_link').css('visibility', 'visible');
		}	
	});
	$('.sortable').disableSelection();
});

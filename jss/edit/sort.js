$(function() {
	$('.sortable').sortable({
		containment: '.sortable-holder',
		handle: '.handler',
		tolerance: 'pointer',
		forceHelperSize: true,
		forcePlaceholderSize: true,
		start: function(event, ui) {
			ui.item.addClass('sort-active');
		},
		stop: function(event, ui) {
			ui.item.removeClass('sort-active');
		}
	});
});

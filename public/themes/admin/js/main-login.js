$(function() {
	// Popover
	$('.popover-test').popover({
		placement: 'left'
	})
	.click(function(e) {
		e.preventDefault()
	});

	$("a[rel=popover]")
		.popover()
	.click(function(e) {
		e.preventDefault()
	});

	// Tooltips
	$('.tip').tooltip();
	$('.focustip').tooltip({'trigger':'focus'});

    // Form elements styling 
    $(".ui-datepicker-month, .styled, .dataTables_length select, input[type=checkbox]").uniform({ radioClass: 'choice' });

});
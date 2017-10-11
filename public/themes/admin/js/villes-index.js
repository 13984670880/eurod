$(function() {
	$('.ref').live('click', function (e) {
	var id = $(this).attr('value');
	var value = $(this).is(':checked');
	if (value)
		value = 1;
	else
		value = 0;
	$.post('/admin/villes/referencement'
			, {
				'value':value
				,'id': id
			},
	function (data) {
		table = $('#data-table').dataTable();
		table.fnDraw();
	}
	, 'json'
			);
	});			
});	
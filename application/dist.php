<script type="text/javascript">
$(function(){ 
	$.ajax({
	   	url: '/admin/users/getusers/format/html',
	   	type: 'POST',
	   	dataType: 'json',
	   	data: {action: 'getUsers'},
	   	complete: function(xhr, textStatus) {
	    	//called when complete
	   	},
	   	success: function(data, textStatus, xhr) {
	     	//called when successful
	     	if(data.success){ 
	     	 	$.each(data, function (index, record){ 
	     	 	 	if ($.isNumeric(index)) {
	     	 	 		var row = $("<tr />");
	     	 	 		$("<td />").text(record.id).appendTo(row);
	     	 	 		$("<td />").text(record.first_name).appendTo(row);
	     	 	 		$("<td />").text(record.last_name).appendTo(row);
	     	 	 		$("<td />").text(record.email).appendTo(row);
	     	 	 		$("<td />").text(record.update_time).appendTo(row);
	     	 	 		row.appendTo('table#data-table');
	     	 	 	}; 
	     	 	}); 
	     	}

     		$('#data-table').dataTable({
     			"bJQueryUI": false,
     			"bAutoWidth": false,
     			"sPaginationType": "full_numbers",
     			"sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
     			"oLanguage": {
     				"sSearch": "_INPUT_",
     				"sLengthMenu": "<span>Show entries:</span> _MENU_",
     				"oPaginate": { "sFirst": "First", "sLast": "Last", "sNext": ">", "sPrevious": "<" }
     			},
     			"aoColumns": [
     				{ "bSortable": false },
     				null,
     				null,
     				null,
     				null,
     			]
     	    });

     	    $(".styled, .dataTables_length select, input[type=checkbox]").uniform({ radioClass: 'choice' });

	   	},
	   	error: function(xhr, textStatus, errorThrown) {
	     	//called when there is an error
	   	}
	});	 	 
});
</script>

<?php
if(!empty($_POST['action'])){ 
	$users = Genius_Model_Global::select(TABLE_PREFIX.'users');
 
$json = array();
foreach ($users as $k => $item) {
 	# code...
	$json[] = array(
		'id'           => $item['id']
		,'first_name'  => $item['first_name']
		,'last_name'   => $item['last_name']
		,'email'       => $item['email']
		,'update_time' => $item['update_time']
	);
} 	 

$json['success'] = true;
echo json_encode($json);
}

$this->translate('Add');
$this->translate('Delete');
$this->translate('Are you sure you want to delete %1$s?', $thing);
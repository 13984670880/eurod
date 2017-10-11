$(function() {
	// Start Delete Module
	$(".delete").live("click", function(e){
		e.preventDefault();
		
		var id = $(this).attr('id');
		bootbox.confirm("Voulez-vous vraiment supprimer cet item?", function(confirmed) {
			if(confirmed){ 
				$.post("/admin/modules/delete"
					,{ 
						"id" : id
					}, 
					function(data){
						table = $('#data-table').dataTable();
						table.fnDraw();		
					}
					,'json'
				);			 	 
			}
		});

	});
	// End Delete Module

});
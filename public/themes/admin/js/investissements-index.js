$(function() {
	// Start Delete User
	$(".delete").live("click", function(e){
		e.preventDefault();

		var id = $(this).attr('id');		
		bootbox.confirm("Voulez-vous vraiment supprimer cet investissement?", function(confirmed) {
			if(confirmed){ 
				$.post("/admin/investissements/delete"
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
	
	// Start Delete User
	$(".deleteindicatif").live("click", function(e){
		e.preventDefault();

		var id = $(this).attr('id');		
		bootbox.confirm("Voulez-vous vraiment supprimer cet item?", function(confirmed) {
			if(confirmed){ 
				$.post("/admin/investissements/deleteindicatif"
					,{ 
						"id" : id
					}, 
					function(data){
						window.location.reload();
					}
					,'json'
				);		 	 
			}
		});

	});
});	

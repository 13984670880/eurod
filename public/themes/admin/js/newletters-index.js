$(function() {
	// Start Delete User
	$(".delete").live("click", function(e){
		e.preventDefault();

		var id = $(this).attr('id');		
		bootbox.confirm("Voulez-vous vraiment supprimer cet item?", function(confirmed) {
			if(confirmed){ 
				$.post("/admin/newsletters/delete"
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
	// End Delete User

	// Check all checbboxes
	$("#checkAll").click(function() {
		var checkedStatus = this.checked;
		$("table#data-table tbody tr td:first-child input:checkbox").each(function() {
			this.checked = checkedStatus;
			if (checkedStatus == this.checked) {
				$(this).closest('.checker > span').removeClass('checked');
			}
			if (this.checked) {
				$(this).closest('.checker > span').addClass('checked');
			}
		});
	});	

	$(".styled").live("click", function(){
		var checkedStatus = this.checked;
		
		if(checkedStatus == true){
			if ($('table#data-table input[type=checkbox]:checked').length == $('table#data-table input[type=checkbox]').length-1) { 
				$("#checkAll").closest('.checker > span').addClass('checked');	 	
			}			
		}else{
			$("#checkAll").closest('.checker > span').removeClass('checked');	
		}
	});
});
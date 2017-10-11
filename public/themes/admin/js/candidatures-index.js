function validate(id_membre_document){
	var data = "id_membre_document="+id_membre_document;				
		$.ajax({
		type:"POST",
		url:"/admin/candidatures/validatecandidature/format/html",
		data:data,
		success:function(response){
			window.location.reload();
		}
		});
}
$(function() {
	$(".checker").uniform();
	
	$("#questionnaire").live("click", function(e){
		var id_membre_document = $('#id_membre_document').val();
		if (this.checked) {
			var questionnaire = 1;
		}else{
			var questionnaire = 0;
		}
		var data = "questionnaire="+questionnaire+"&id_membre_document="+id_membre_document;				
		$.ajax({
		type:"POST",
		url:"/admin/candidatures/updatequestionnaire/format/html",
		data:data,
		success:function(response){
		}
		});
	});
	$("#compte_valid").live("click", function(e){
		var id_membre = $('#id_membre').val();
		if (this.checked) {
			var compte_valid = 1;
		}else{
			var compte_valid = 0;
		}
		var data = "compte_valid="+compte_valid+"&id_membre="+id_membre;				
		$.ajax({
		type:"POST",
		url:"/admin/candidatures/updatestatecompte/format/html",
		data:data,
		success:function(response){
		}
		});
	});
	// Start Delete User
	$(".delete").live("click", function(e){
		e.preventDefault();

		var id = $(this).attr('id');		
		bootbox.confirm("Voulez-vous vraiment supprimer cet item?", function(confirmed) {
			if(confirmed){ 
				$.post("/admin/news/delete"
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

	// Starting Mass Delete
	$(".massdelete").live("click", function(e){
		e.preventDefault();
		var massdeleteitems = $('input[name="checked[]"]').serializeArray();
		
		if ( (typeof massdeleteitems === 'undefined') || (massdeleteitems === null) || ($.isEmptyObject(massdeleteitems)) ) { 
			e.preventDefault();
			bootbox.alert("Veuillez séléctionner au moins un item?", function() {
			});
		}else{ 
			bootbox.confirm("Voulez-vous vraiment supprimer ces items?", function(confirmed) {
				if(confirmed){ 
					$.post("/admin/news/massdelete"
						,{ 
							"massdeleteitems"  : massdeleteitems
						}, 
						function(data){
							table = $('#data-table').dataTable();
							table.fnDraw();	
						}
						,'json'
					); 
				}
			});
		}
	});
	// Ending Mass Delete

});
$(function() {
    $("#ville").autocomplete({
        //source: "/admin/annonces/getvilles/format/html",
        source: function(request, response) {
            $.ajax({
                url: "/admin/annonces/getvilles/format/html",
                dataType: "json",
                data: {
                    term: request.term
                    ,id_departement: $("#id_departement").val()
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        autoFocus: true,
        minLength: 2,
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            $('#ville').val(ui.item.ville);
            $("#id_ville").val(ui.item.id);
            return false;
        }
    }).data('autocomplete')._renderItem = function(ul, item) {
        return $("<li></li>")
            .data("item.autocomplete", item)
            .append("<a>" + item.ville + "</a>")
            .appendTo(ul);
    };

    $('form input#ville').data('val',  $('form input#ville').val() );
    $('form input#ville').change(function(){
        if($(this).val()===''){
        	console.log('vide');
        	$('form input#id_ville').val(''); 
        }
    });
    $('form input#ville').keyup(function() { 
        if( $('form input#ville').val() != $('form input#ville').data('val') ){ 
            $('form input#ville').data('val',  $('form input#ville').val() ); 
            $(this).change();
        }
    });
});

function getDepartement(id_region){
	var data = "id_region="+id_region;				
	$.ajax({
		type:"POST",
		url:"/admin/annonces/getdepartements/format/html",
		data:data,
		success:function(response){
			$("#id_departement").html(response);
		}
	});
}

/*
function getVille(id_departement){
	var data = "id_departement="+id_departement;				
	$.ajax({
		type:"POST",
		url:"/admin/annonces/getvilles/format/html",
		data:data,
		success:function(response){
			$("#ville").html(response);
		}
	});
}
*/
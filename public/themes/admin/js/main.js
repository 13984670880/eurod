$(function() {
	// fullview : show/hide menu
	$('.fullview').click(function(){
	    $("body").toggleClass("clean");
	    $('#sidebar').toggleClass("hide-sidebar mobile-sidebar");
	    $('#content').toggleClass("full-content");
	});

	// expandable menu
	// $('.expand').collapsible({
	// 	defaultOpen: 'current,third',
	// 	cookieName: 'navAct',
	// 	cssOpen: 'subOpened',
	// 	cssClose: 'subClosed',
	// 	speed: 200
	// });

	$('.expand').live('click', function(){ 
		$(this).next('ul').toggle(200);		 
	});

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

    // Validation
    $(".form-to-validate").validationEngine({promptPosition : "topRight:-122,-5"});

    $(".dataTables_length select, #checkAll, .styled").uniform({ radioClass: 'choice' });

    // Datepicker
    $( ".datepicker" ).datepicker({
    	defaultDate: +7,
    	showOtherMonths:true,
    	autoSize: true,
    	dateFormat: 'yy-mm-dd'
    });

    // tabs multilingual
    $("ul.nav-multilingual li").click(function() {
    	$(this).parent().parent().find("ul.nav-multilingual li").removeClass("active");
    	$(this).addClass("active"); 
    	
    	$(this).parent().parent().parent().find(".tab-form-content .multilingual").hide(); 

    	var activeTab = $(this).find("a").attr("abbr"); 
    	$('.'+activeTab).show(); 

    	$('.cke').hide(); 
		$('#cke_accroche_'+activeTab).show();
    	$('#cke_text_'+activeTab).show(); 
		$('#cke_text_seo_'+activeTab).show(); 
		$('#cke_references_'+activeTab).show();
		$('#cke_text_reparation_'+activeTab).show();
		$('#cke_text_vente_'+activeTab).show();
		$('#cke_text_echange_'+activeTab).show();
		$('#cke_text_maintenance_'+activeTab).show();
		$('#cke_text_location_'+activeTab).show();
		$('#cke_text_audit_'+activeTab).show();
		$('#cke_text_reprise_'+activeTab).show();
		
    	
    	$(this).parent().parent().parent().find(".tab-form-content .ckeditor").css({'display':'none', 'visibility':'hidden'}); 
    	  
    	return false;
    });

    $(".lightbox").fancybox({
    	'padding': 2
    });

    // change lang
	$('.change-lang').click(function(){
		$.post("/admin/translations/changelang"
			,{ 
				"action" : 'changelang'
			}, 
			function(data){
				window.location.reload();	
			}
			,'json'
		);    
	});  

	$(window).resize(function(){ 
		adjustContentHeight(); 
	});

});

$(window).load(function(){ 
	// adjust content height
	adjustContentHeight(); 
});

function adjustContentHeight()
{ 
	var body_h = $("body").height();
	var content_h = body_h - 90;
	content_h = parseInt(content_h);
	$("#content").css({'min-height':content_h});	 
}
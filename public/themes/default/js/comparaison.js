var printableArea = document.getElementById('printableArea');
html2canvas(printableArea, {
    onrendered: function (canvas) {
        var img = canvas.toDataURL("image/png");
        var img_b = '<img download="tarifs.jpg" src="' + img + '"/>';
        var download_link = '<a class="bt_suppr" download="tableau_comparatif.jpg" href="' + img + '" download>Télécharger le tableau</a></p>';
        $('#content_link').html(download_link);
    }
});

function sendcomparaison(t) {
    var mail = $('#mail').val();
    var data = "t=" + t+"&mail="+mail;
    $.ajax({
        type: "POST",
        url: "/comparaison/compare/format/html",
        data: data,
        success: function (e) {
            var datas = $.parseJSON((e));
            if(datas.state != 1){
               $('#error').html('<p class="class-error">'+datas.msg+'</p>'); 
            }else{
                $('#error').html('<p class="class-succes">'+datas.msg+'</p>');
				window.history.back();
            }
        }
    });
}

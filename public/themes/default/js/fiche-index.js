function getImage(id_image) {
    var urlR = $(".img").attr("src");
    var urlD = $(".thumb_" + id_image).attr("src");
    $(".img").attr("src", urlD);
    $(".thumb_" + id_image).attr("src", urlR);
    $(".fancyboxx").attr("href", urlD);
    return false;
}

function addProductList(id, id_cat) {
    var data = "id=" + id + "&id_cat=" + id_cat;
    $.ajax({
        type: "POST",
        url: "/fiche/addproduct/format/html",
        data: data,
        success: function (e) {
            location.reload();
        }
    })
}
function deleteProductList(id, id_cat) {
    var data = "id=" + id + "&id_cat=" + id_cat;
    $.ajax({
        type: "POST",
        url: "/fiche/deleteproduct/format/html",
        data: data,
        success: function (e) {
            location.reload();
        }
    })
}

function deleteAllProduct(id_cat) {
    var data = "id_cat=" + id_cat;
    $.ajax({
        type: "POST",
        url: "/fiche/deleteallproduct/format/html",
        data: data,
        success: function (e) {
            location.reload();
        }
    })
}


    var thumb = '';
    var bool = false;
    var v;
    $('.thumb').hover(function () {
        v = $('#miniatures').attr('src');
        var current = $(this).attr('id');
        thumb = $('#' + current).attr("src");
        $('#miniatures').attr('src', thumb);
    }, function () {
        $("#miniatures").attr('src', v);
    });

    $('.thumb').click(function () {
        var current = $(this).attr('id');
        thumb = $('#' + current).attr("src");
        $("#" + current).attr("src", v);
        $("#miniatures").attr("src", thumb);

        v = thumb;
    });





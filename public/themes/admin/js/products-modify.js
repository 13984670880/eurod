/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function getRubrique() {
    var id_rubrique = $('#rubriques').val();
    var data = 'id=' + id_rubrique;
    $('#groupes').empty();
    $.ajax({
        type: "POST",
        url: "/admin/products/getgroups/format/html",
        data: data,
        success: function (response) {
            var datas = JSON.parse((response));
            if (datas.value != '') {
                $('#groupes').append(datas.value);
                $('#marques').html('');
                $('#types').html('');
            }
        },
        error: function (response) {
            console.log(error);
        }
    });
}

function getGroupes() {
    var id_groupe = $('#groupes').val();
    var data = 'id=' + id_groupe;
    $('#title-marques').empty();
    $('#marques').empty();
    $.ajax({
        type: "POST",
        url: "/admin/products/getmarquesandtypes/format/html",
        data: data,
        success: function (response) {
            var datas = JSON.parse((response));
            if (datas.status == 1) {
                $('#title-marques').html(datas.title_1);
                $('#marques').html(datas.value_1);
                $('#title-types').html(datas.title_2);
                $('#types').html(datas.value_2);
            } else {

            }
        },
        error: function (response) {
            console.log(error);
        }
    });
}

function deletepdf(id_product) {
    var data = "id="+id_product;
    $.ajax({
        type: "POST",
        url: "/admin/products/deletepdf/format/html",
        data: data,
        success: function (response) {
            var datas = JSON.parse((response));
            if (datas == 1) {
                window.location.reload();
            } else {

            }
        },
        error: function (response) {
            console.log(error);
        }
    });
}


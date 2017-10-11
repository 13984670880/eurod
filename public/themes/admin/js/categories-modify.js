$(function () {

    // Change on group select : load order item
    $("#id_category_group").live("change", function (e) {
        var id_category_group = $("#id_category_group option:selected").val();
        $.post("/admin/categories/getajaxorderitem"
                , {
                    "id_category_group": id_category_group
                },
        function (data) {
            $("#order_item").html(data);
        }
        );
    });


});

$('input[name="is_offre_speciale"]:radio').change(function () {
    if (this.value == 1) {
        $('.display_offre_speciale').show();
    } else {
        $('.display_offre_speciale').hide();
        $('input[name="activation_offre_speciale"]:radio').prop('checked', false);
    }
});

function getcategories(id_category_group) {
    var data = "id_category_group=" + id_category_group;
    $.ajax({
        type: "POST",
        url: "/admin/categories/getcategoriesbygroup/format/html",
        data: data,
        success: function (datas) {
            $("#categories_offres_speciales").html(datas);
        },
        error: function (response) {
            console.log(error);
        }
    });
}


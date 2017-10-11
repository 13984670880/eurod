$(function() {
    $(".delete-photo-piece").live("click", function(e) {
        e.preventDefault();

        var id = $(this).attr('id');
        bootbox.confirm("Voulez-vous vraiment supprimer cet item?", function(confirmed) {
            if (confirmed) {
                $.post("/admin/annonces/deletephotopiece"
                        , {
                    "id": id
                },
                function(data) {
                    $("#" + id).hide();
                }
                , 'json'
                        );
            }
        });
    });

    $("#ville").autocomplete({
        source: "/admin/annonces/getvilles/format/html",
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
});


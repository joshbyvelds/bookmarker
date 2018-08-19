//# sourceMappingURL=master.js.map

function setupDropzone(){
    Dropzone.autoDiscover = false;
    $("#new_bookmark_image").addClass("dropzone").dropzone( { url: "/php/images_upload.php", thumbnailWidth:300, thumbnailHeight:225, renameFile:"new_bookmark_image" });
}

function setupNewGroupSubmit(){
    $("#modal_groups_form .alert").hide();

    $( "#new_bookmark_form" ).submit(function( event ) {
        event.preventDefault();
        $.post("php/group.php", $(this).serialize(), function (json_return) {

        });
    });
}

function setupNewBookmarkSubmit(){
    $("#modal_bookmarks_form .alert").hide();

    $( "#new_bookmark_form" ).submit(function( event ) {
        event.preventDefault();
        $.post("php/bookmark.php", $( this ).serialize(), function(json_return) {
            json_return = JSON.parse(json_return);

            if (json_return.error) {
                if (json_return.general_error) {
                    $("#new_bookmark_general_error").html(json_return.general_error).slideDown();
                }

                if (json_return.title_error) {
                    $("#new_bookmark_title_error").html(json_return.title_error).slideDown();
                }

                if(json_return.url_error) {
                    $("#new_bookmark_url_error").html(json_return.url_error).slideDown();
                }
            }else{

                // Add Bookmark to The Grid.
                var title = "";
                var titleStart = $("#bookmark_form_title").val().split(" ").slice(0, -1).join(" ");
                var titleEnd = $("#bookmark_form_title").val().split(" ").pop();

                title = titleStart + " " + "<span>"+ titleEnd +"</span>";

                $("#the_grid").append("<div class=\"grid_item\" data-id=\""+ json_return.last_id +"\">\n" +
                    "            <figure class=\"effect-zoe\">\n" +
                    "                <a href=\""+ $("#bookmark_form_url").val() +"\" target=\"_blank\"><img src=\"img/thumbnails/"+ json_return.image +".jpg\" alt=\"img25\"></a>\n" +
                    "                <figcaption>\n" +
                    "                    <h2>"+ title +"</h2>\n" +
                    "                    <p class=\"icon-links\">\n" +
                    "                        <a href=\"#\" class=\"stats\"><i class=\"fas fa-chart-pie\"></i></a>\n" +
                    "                        <a href=\"#\" class=\"edit\"><i class=\"fas fa-edit\"></i></a>\n" +
                    "                        <a href=\"#\" class=\"fav\"><i class=\"fas fa-thumbs-up\"></i></a>\n" +
                    "                    </p>\n" +
                    "                </figcaption>\n" +
                    "            </figure>\n" +
                    "        </div>");

                // Reset New Bookmark Form..
                Dropzone.forElement("#new_bookmark_image").removeAllFiles(true);
                $("#bookmark_form_url").val("");
                $("#bookmark_form_title").val("");
                $("#new_bookmark_type").val("1");

                // Close Modal..
                $('#modalBookmarkForm').modal('toggle');
            }
        });
    });
}

function setupBookmarkVisit(){
    $(".bookmark_link").off("click").click(function(){
        var id = $(this).parent().parent().data("id");
        $.post("php/bookmark.php", {"type":"visit", "id":id}, function(){
        });
    });
}

function checkTitleFonts(){
    // Get width of all 3 icons..
    // Get width of bookmark.

    // for each bookmark..
    // Check to see if title width > bookmark width - 3 icons

    // if it is.. reduce title font size by 1 px; and check again..

}

function setupFavorites(){
    // Setup like and dislike buttons..
    $(".grid_item .fav").off().on('click', function(){
        var $grid_item = $(this).parents('.grid_item');
        $.post("php/bookmark.php", {"type":"like", "id":$grid_item.data("id")}, function(){
            $(".free_slot").first().replaceWith( "<a href=\""+ $grid_item.find("a").attr('href') +"\" target=\"_blank\" class=\"favorite_link favorite_item\"><div class=\"remove-icon\" data-id=\""+ $grid_item.data('id') +"\"><i class=\"fas fa-trash-alt\"></i></div><img src=\""+ $grid_item.find("img").attr("src") +"\" alt=\"img25\"></a>" );
        });
    });

    $(".favorite_link .remove-icon").off().on('click', function(event){
        event.stopPropagation();
        $.post("php/bookmark.php", {"type":"unlike", "id":$(this).parents('.grid_item').data("id")}, function(){
            $(this).parent().replaceWith("<div class=\"free_slot\"><i class=\"fas fa-thumbs-up\"></i><img src=\"img/empty.png\" /></div>");
        });
    });

}

function init() {
    setupDropzone();
    setupNewBookmarkSubmit();
    setupBookmarkVisit();
    setupNewGroupSubmit();
    setupFavorites();
    checkTitleFonts();
}

$(document).ready(init);
//# sourceMappingURL=master.js.map

function setupDropzone(){
    Dropzone.autoDiscover = false;
    $("#new_bookmark_image").addClass("dropzone").dropzone( { url: "/php/images_upload.php", thumbnailWidth:300, thumbnailHeight:225, renameFile:"new_bookmark_image" });
}

function setupNewBookmarkSubmit(){
    $("#modalBookmarkForm .alert").hide();

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

function init() {
    setupDropzone();
    setupNewBookmarkSubmit();
    setupBookmarkVisit();
}

$(document).ready(init);
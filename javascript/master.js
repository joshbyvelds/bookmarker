//# sourceMappingURL=master.js.map

$(document).ready(init);

function init() {
    setupDropzone();
    setupNewBookmarkSubmit();
}

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

                $("#bookmark_form_url").val("");
                $("#bookmark_form_title").val("");
                $("#new_bookmark_type").val("1");
                $('#modalBookmarkForm').modal('toggle');
            }
        });
    });
}

function slide(){
    $("#leftside_panel").on('mouseenter', function(){
        if(slidelock){return;}else{slidelock = true;}
        var left_width = 175;
        var grid_width = $(window).width() - left_width - 2;
        $("#left_panel_logo").fadeOut(500, function(){$(this).attr("src", "img/logo_med.png").fadeIn(500)});
        $("#leftside_panel").width($("#leftside_panel").width() - 2).animate({width:left_width + "px"}, 500);
        $("#the_grid").animate({width:grid_width + "px"}, 500, function(){
            $(".icon_word-js").fadeIn(250);
            slidelock = false;
        });
    });

    $("#leftside_panel").on('mouseleave', function(){
        if(slidelock){return;}else{slidelock = true;}
        var left_width = 55;
        var grid_width = $(window).width() - left_width;
        $("#left_panel_logo").fadeOut(500, function(){$(this).attr("src", "img/logo_small.png").fadeIn(500)});
        $("#leftside_panel").animate({width:left_width + "px"}, 500);
        $("#the_grid").animate({width: grid_width + "px"}, 500, function(){
            $("#the_grid").attr("style", "");
            slidelock = false;
        });
        $(".icon_word-js").hide();
    });
}

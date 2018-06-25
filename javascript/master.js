//# sourceMappingURL=master.js.map

$(document).ready(init);

function init() {
    var slidelock = false;

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

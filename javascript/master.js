//# sourceMappingURL=master.js.map

$(document).ready(init);

function init() {
    var slidelock = false;

    $("#leftside_panel").on('mouseenter', function(){
        if(slidelock){return;}else{slidelock = true;}
        var left_width = 175;
        var grid_width = $(window).width() - left_width;
        $("#leftside_panel").animate({width:left_width + "px"}, 500);
        $("#the_grid").animate({width:grid_width + "px"}, 500, function(){
            $(".icon_word-js").fadeIn(250);
            slidelock = false;
        });
    });

    $("#leftside_panel").on('mouseleave', function(){
        if(slidelock){return;}else{slidelock = true;}
        var left_width = 55;
        var grid_width = $(window).width() - left_width;
        $("#leftside_panel").animate({width:left_width + "px"}, 500);
        $("#the_grid").animate({width: grid_width + "px"}, 500, function(){
            $("#the_grid").attr("style", "");
            slidelock = false;
        });
        $(".icon_word-js").hide();
    });
}

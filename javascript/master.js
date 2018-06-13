//# sourceMappingURL=master.js.map

$(document).ready(init);

function init() {
    $("#leftside_panel").on('mouseenter', function(){
        $("#leftside_panel").animate({width:"175px"}, 500);
        $("#the_grid").animate({width:"1720px"}, 500, function(){
            $(".icon_word-js").fadeIn(250);
        });
    });

    $("#leftside_panel").on('mouseleave', function(){
        $("#leftside_panel").animate({width:"55px"}, 500);
        $("#the_grid").animate({width:"1848px"}, 500);
        $(".icon_word-js").hide();
    });
}

$(".pagebar a").click(function() {
    var slink=$(this).attr("href");
    $(".pagebar").parent().load(slink);
    return false;
});

$(".pagebar input").keypress(function(event) {
    var keycode=(event.keyCode?event.keyCode:event.which);
    if (keycode=='13') {
        var place=$(this).val()-1;
        if (!isNaN(place)) {
            var path=$(this).attr("name")+"="+place;
            $(".pagebar").parent().load(path);
        }
    }
});

$(function () {
    $("iframe").each(function () {
        var attr = $(this).attr("title");
        console.log(attr);
        if (typeof attr === typeof undefined) {
            $(this).attr("title", "Iframe loaded from external site");
        }
    });
});
jQuery(function () {
    jQuery("iframe").each(function () {
        var attr = jQuery(this).attr("title");
        console.log(attr);
        if (typeof attr === typeof undefined) {
            jQuery(this).attr("title", "Iframe loaded from external site");
        }
    });
});
/* jshint esversion: 6 */
(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypeSoapLogin = {
        attach: function (context, settings) {
            $(".etype_logged_in").click(function (e) {
                let arr;
                e.preventDefault();
                arr = $(this).attr("href").split("/");
                console.log(arr);
            });
        }
    };
})(jQuery, Drupal);
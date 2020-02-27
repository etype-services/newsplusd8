/* jshint esversion: 6 */
(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypeSoapLogin = {
        attach: function (context, settings) {
            $(".etype_logged_in").click(function (e) {
                e.preventDefault();
                let arr = $(this).attr("href").split("/");
                $.soap({
                    url: 'https://publisher.etype.services/webservice.asmx',
                    method: 'GenerateUrlForSubscriber',

                    params: {
                        publicationId: 1,
                        usenamr: 'alind'
                    },

                    success: function (soapResponse) {
                        // do stuff with soapResponse
                        // if you want to have the response as JSON use soapResponse.toJSON();
                        // or soapResponse.toString() to get XML string
                        // or soapResponse.toXML() to get XML DOM
                        console.log(soapResponse);
                    },
                    error: function (SOAPResponse) {
                        // show error
                        console.log(soapResponse);
                    }
                });
            });
        }
    };
})(jQuery, Drupal);
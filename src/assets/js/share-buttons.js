/*!
 * Contact Buttons Plugin Demo 0.1.0
 * https://github.com/joege/contact-buttons-plugin
 *
 * Copyright 2015, José Gonçalves
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

(function () {


    // Define the container for the buttons
    var oContainer = $("#contact-buttons-bar");

    // Make the buttons visible
    setTimeout(function(){
        oContainer.animate({ left : 0 });
    }, 200);

    // Show/hide buttons
    $('body').on('click', '.show-hide-contact-bar', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        $('.show-hide-contact-bar').find('.fa').toggleClass('fa-angle-right fa-angle-left');
        oContainer.find('.cb-ancor').toggleClass('cb-hidden');
    });

    $('body').on('click', '.contact-button-link', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        window.open($(this).attr("href"), "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=150, left=400, width=500, height=400");
    });

}( jQuery ));

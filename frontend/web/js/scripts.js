jQuery(document).ready(function ($) {


    /*====== BACK TO TOP ======*/
    $('.back-to-top-page').each(function () {
        $('.back-to-top').on('click', function (event) {
            event.preventDefault();
            $('html, body').animate({scrollTop: 0}, 1500);
            return false;
        });
    });

});
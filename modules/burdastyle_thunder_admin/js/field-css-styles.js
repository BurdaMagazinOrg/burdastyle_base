(function ($) {
    $('.field--name-field-css-styles textarea').click(function(){
        if (!$('.field--name-field-css-styles textarea').val()) {
            $('.field--name-field-css-styles textarea').val($('.field--name-field-css-styles textarea').attr('placeholder'));
        }
    });
}(jQuery));

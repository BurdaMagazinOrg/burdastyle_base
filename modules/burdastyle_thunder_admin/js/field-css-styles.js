(function ($) {
    $('.field--name-field-css-styles textarea').click(function(){
        if (!$('.field--name-field-css-styles textarea').val()) {
            var cssTemplate = $('.field--name-field-css-styles textarea').attr('placeholder');
            $('.field--name-field-css-styles textarea').val(cssTemplate);
        }
    });
}(jQuery));

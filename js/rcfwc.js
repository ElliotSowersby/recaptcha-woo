/* Woo Checkout */
jQuery( document ).ready(function() {
    if(jQuery('.g-recaptcha').length > 0) {
        jQuery( document.body ).on( 'update_checkout updated_checkout applied_coupon_in_checkout removed_coupon_in_checkout', function() {
            grecaptcha.reset();
        });
        jQuery( document.body ).on( 'checkout_error', function() {
            grecaptcha.reset();
        });
    }
});
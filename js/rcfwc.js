/* Woo Checkout */
jQuery( document ).ready(function() {
    jQuery( document.body ).on( 'update_checkout updated_checkout applied_coupon_in_checkout removed_coupon_in_checkout checkout_error', function() {
        if(jQuery('.g-recaptcha').length > 0) {
            if (typeof grecaptcha !== "undefined" && typeof grecaptcha.reset === "function") {
                var count = 0;
                jQuery(".g-recaptcha").each(function () {
                    grecaptcha.reset(count);
                    count++;
                });
            }
        }
    });
});
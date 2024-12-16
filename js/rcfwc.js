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

/* Woo Checkout Block */
if ( wp && wp.data ) {
    var unsubscribe = wp.data.subscribe(function() {
        const recaptcha = document.querySelector('.g-recaptcha');
        if(recaptcha) {
            grecaptcha.render(recaptcha, {
                sitekey: recaptcha.dataset.sitekey,
                callback: function(data) {
                    wp.data.dispatch('wc/store/checkout').__internalSetExtensionData('rcfwc', {
                        token: data
                    })
                }
            });
        }
        unsubscribe();
    }, 'wc/store/cart');
}
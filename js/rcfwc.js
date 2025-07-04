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
    let isRecaptchaRendered = false;
    let recaptchaWidgetId = null;
    let hasValidToken = false;
    
    const renderRecaptcha = function() {
        const recaptcha = document.querySelector('.g-recaptcha:not([data-rendered])');
        if (recaptcha && typeof grecaptcha !== 'undefined' && !isRecaptchaRendered) {
            try {
                recaptchaWidgetId = grecaptcha.render(recaptcha, {
                    sitekey: recaptcha.dataset.sitekey,
                    theme: recaptcha.dataset.theme || 'light',
                    callback: function(token) {
                        hasValidToken = true;
                        wp.data.dispatch('wc/store/checkout').__internalSetExtensionData('rcfwc', {
                            token: token
                        });
                    },
                    'expired-callback': function() {
                        hasValidToken = false;
                        wp.data.dispatch('wc/store/checkout').__internalSetExtensionData('rcfwc', {
                            token: ''
                        });
                    }
                });
                recaptcha.setAttribute('data-rendered', 'true');
                isRecaptchaRendered = true;
            } catch (error) {
                console.log('reCAPTCHA render error:', error);
            }
        }
    };

    // Wait for grecaptcha to be available
    const waitForRecaptcha = function() {
        if (typeof grecaptcha !== 'undefined' && grecaptcha.render) {
            renderRecaptcha();
        } else {
            setTimeout(waitForRecaptcha, 100);
        }
    };

    // Subscribe to checkout updates - only for initial rendering
    let hasSubscribed = false;
    const unsubscribe = wp.data.subscribe(function() {
        if (!hasSubscribed) {
            const checkoutData = wp.data.select('wc/store/checkout');
            if (checkoutData && document.querySelector('.g-recaptcha')) {
                waitForRecaptcha();
                hasSubscribed = true;
                unsubscribe();
            }
        }
    });

    // Also try to render on DOM changes
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                const recaptcha = document.querySelector('.g-recaptcha:not([data-rendered])');
                if (recaptcha) {
                    waitForRecaptcha();
                }
            }
        });
    });

    // Start observing when checkout block is present
    if (document.querySelector('.wp-block-woocommerce-checkout')) {
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
}
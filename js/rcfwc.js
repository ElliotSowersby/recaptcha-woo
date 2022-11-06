jQuery( document ).ready(function() {
	jQuery( document.body ).on( 'checkout_error', function(){
		if (document.getElementsByClassName('g-recaptcha')) {
			grecaptcha.reset();
		}
	});
});

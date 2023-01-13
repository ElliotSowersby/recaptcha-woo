<?php
/**
* Plugin Name: reCAPTCHA for WooCommerce
* Description: Add Google reCAPTCHA to your WooCommerce Checkout, Login, and Registration Forms.
* Version: 1.2.9
* Author: Elliot Sowersby, RelyWP
* Author URI: https://www.relywp.com
* License: GPLv3 or later
* Text Domain: recaptcha-woo
*
* WC requires at least: 3.4
* WC tested up to: 7.1.1
**/

include( plugin_dir_path( __FILE__ ) . 'admin-options.php');

/**
 * On activate redirect to settings page
 */
register_activation_hook(__FILE__, function () {
  add_option('rcfwc_do_activation_redirect', true);
	add_option('rcfwc_tested', 'no');
});
add_action('admin_init', function () {
  if (get_option('rcfwc_do_activation_redirect', false)) {
    delete_option('rcfwc_do_activation_redirect');
    exit( wp_redirect("options-general.php?page=recaptcha-woo%2Fadmin-options.php") );
  }
});

// Plugin List - Settings Link
add_filter( 'plugin_action_links', 'rcfwc_settings_link_plugin', 10, 5 );
function rcfwc_settings_link_plugin( $actions, $plugin_file )
{
	static $plugin;

	if (!isset($plugin))
		$plugin = plugin_basename(__FILE__);
	if ($plugin == $plugin_file) {
		$settings = array('settings' => '<a href="options-general.php?page=recaptcha-woo%2Fadmin-options.php">' . __('Settings', 'General') . '</a>');
    	$actions = array_merge($settings, $actions);
	}

	return $actions;
}

// Enqueue recaptcha script only on account or checkout page
add_action("wp_enqueue_scripts", "rcfwc_script_enqueue");
function rcfwc_script_enqueue() {
  wp_enqueue_script( 'rcfwc-js', plugins_url( '/js/rcfwc.js', __FILE__ ), array('jquery'), '1.0', false);
	wp_register_script("recaptcha", "https://www.google.com/recaptcha/api.js?explicit&hl=" . get_locale());
	wp_enqueue_script("recaptcha");
}
add_action("wp_enqueue_scripts", "rcfwc_script");
function rcfwc_script() {
  if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
  	if ( is_checkout() || is_account_page() ) {
  		 rcfwc_script_enqueue();
  	}
  }
}
// Enqueue recaptcha script on login
add_action("login_enqueue_scripts", "rcfwc_script_login");
function rcfwc_script_login() {
	rcfwc_script_enqueue();
}

// Field
function rcfwc_field() {
	$key = esc_attr( get_option('rcfwc_key') );
	$secret = esc_attr( get_option('rcfwc_secret') );
	$theme = esc_attr( get_option('rcfwc_theme') );
	if($key && $secret) {
		?>
		<div class="g-recaptcha" <?php if($theme == "dark") { ?>data-theme="dark" <?php } ?>data-sitekey="<?php echo $key; ?>"></div>
		<br/>
		<?php
	}
}

// Field WP Admin
function rcfwc_field_admin() {
	$key = esc_attr( get_option('rcfwc_key') );
	$secret = esc_attr( get_option('rcfwc_secret') );
	$theme = esc_attr( get_option('rcfwc_theme') );
	if($key && $secret) {
		?>
		<div style="margin-left: -15px;" class="g-recaptcha" <?php if($theme == "dark") { ?>data-theme="dark" <?php } ?>data-sitekey="<?php echo $key; ?>"></div>
		<br/>
		<?php
	}
}

// Field Checkout
function rcfwc_field_checkout($checkout) {
	$key = esc_attr( get_option('rcfwc_key') );
	$secret = esc_attr( get_option('rcfwc_secret') );
	$theme = esc_attr( get_option('rcfwc_theme') );
	$guest = esc_attr( get_option('rcfwc_guest_only') );
	if( !$guest || ( $guest && !is_user_logged_in() ) ) {
		if($key && $secret) {
		?>
		<div class="g-recaptcha" <?php if($theme == "dark") { ?>data-theme="dark" <?php } ?>data-sitekey="<?php echo $key; ?>"></div>
		<br/>
		<?php
		}
	}
}

// Check the reCAPTCHA on submit.
function rcfwc_recaptcha_check() {

	$postdata = "";
	if(isset($_POST['g-recaptcha-response'])) {
		$postdata = sanitize_text_field( $_POST['g-recaptcha-response'] );
	}

	$key = esc_attr( get_option('rcfwc_key') );
	$secret = esc_attr( get_option('rcfwc_secret') );
	$guest = esc_attr( get_option('rcfwc_guest_only') );

	if($key && $secret) {

		$verify = wp_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$postdata );
		$verify = wp_remote_retrieve_body( $verify );
		$response = json_decode($verify);

		$results['success'] = $response->success;

		foreach($response as $key => $val){
			if($key == 'error-codes')
			foreach($val as $key => $error_val){
				$results['error_code'] = $error_val;
			}
		}

		return $results;

	} else {

		return false;

	}

}

if(!empty(get_option('rcfwc_key')) && !empty(get_option('rcfwc_secret'))) {

	// WP Login Check
	if(get_option('rcfwc_login')) {
		if(get_option('rcfwc_tested') == 'yes') {
			add_action('login_form','rcfwc_field_admin');
			add_action('authenticate', 'rcfwc_wp_login_check', 21, 1);
			function rcfwc_wp_login_check($user){
				if(is_wp_error($user) && isset($user->errors['empty_username']) && isset($user->errors['empty_password'])) {	return $user; } // Skip Errors
				if(stripos($_SERVER["REQUEST_URI"], strrchr(wp_login_url(), '/')) !== false) { // Check if WP login page
					$check = rcfwc_recaptcha_check();
					$success = $check['success'];
					if($success != true) {
						$user = new WP_Error( 'authentication_failed', __( 'Please complete the reCAPTCHA to verify that you are not a robot.', 'recaptcha-woo' ) );
					}
				}
				return $user;
			}
		}
	}

	// WP Register Check
	if(get_option('rcfwc_register')) {
		add_action('register_form','rcfwc_field_admin');
		add_action('registration_errors', 'rcfwc_wp_register_check', 10, 3);
		function rcfwc_wp_register_check($errors, $sanitized_user_login, $user_email) {
			if(defined( 'XMLRPC_REQUEST')) { return $errors; } // Skip XMLRPC
			$check = rcfwc_recaptcha_check();
			$success = $check['success'];
			if($success != true) {
				$errors->add( 'rcfwc_error', sprintf('<strong>%s</strong>: %s',__( 'ERROR', 'recaptcha-woo' ), __( 'Please complete the reCAPTCHA to verify that you are not a robot.', 'recaptcha-woo' ) ) );
			}
			return $errors;
		}
	}

	// WP Reset Check
	if(get_option('rcfwc_woo_reset')) {
	  if(!is_admin()) {
	  	add_action('lostpassword_form','rcfwc_field_admin');
	  	add_action('lostpassword_post','rcfwc_wp_reset_check', 10, 1);
	  	function rcfwc_wp_reset_check($validation_errors) {
			if(stripos($_SERVER["REQUEST_URI"], strrchr(wp_login_url(), '/')) !== false) { // Check if WP login page
	  			$check = rcfwc_recaptcha_check();
	  			$success = $check['success'];
	  			if($success != true) {
	  				$validation_errors->add( 'rcfwc_error', __( 'Please complete the reCAPTCHA to verify that you are not a robot.', 'recaptcha-woo' ) );
	  			}
	  		}
	  	}
	  }
	}

  if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

  	// Woo Checkout
  	if( get_option('rcfwc_key') && get_option('rcfwc_woo_checkout') ) {
  		add_action('woocommerce_review_order_before_payment', 'rcfwc_field_checkout', 10);
  		add_action('woocommerce_checkout_process', 'rcfwc_checkout_check');
  		function rcfwc_checkout_check() {
  			$guest = esc_attr( get_option('rcfwc_guest_only') );
  			if( !$guest || ( $guest && !is_user_logged_in() ) ) {
  				$check = rcfwc_recaptcha_check();
  				$success = $check['success'];
  				if($success != true) {
  					wc_add_notice( __( 'Please complete the reCAPTCHA to verify that you are not a robot.', 'recaptcha-woo' ), 'error');
  				}
  			}
  		}
  	}

  	// Woo Login
  	if(get_option('rcfwc_woo_login')) {
  		add_action('woocommerce_login_form','rcfwc_field');
  		add_action('wp_authenticate_user', 'rcfwc_woo_login_check', 10, 1);
  		function rcfwc_woo_login_check($user){
  			if(isset($_POST['woocommerce-login-nonce'])) {
  				$check = rcfwc_recaptcha_check();
  				$success = $check['success'];
  				if($success != true) {
  					$user = new WP_Error( 'authentication_failed', __( 'Please complete the reCAPTCHA to verify that you are not a robot.', 'recaptcha-woo' ) );
  				}
  			}
  			return $user;
  		}
  	}

  	// Woo Register
  	if(get_option('rcfwc_woo_register')) {
  		add_action('woocommerce_register_form','rcfwc_field');
  		add_action('woocommerce_register_post', 'rcfwc_woo_register_check', 10, 3);
  		function rcfwc_woo_register_check($username, $email, $validation_errors) {
  			if(!is_checkout()) {
  				$check = rcfwc_recaptcha_check();
  				$success = $check['success'];
  				if($success != true) {
  					$validation_errors->add( 'rcfwc_error', __( 'Please complete the reCAPTCHA to verify that you are not a robot.', 'recaptcha-woo' ) );
  				}
  			}
  		}
  	}

  	// Woo Reset
  	if(get_option('rcfwc_woo_reset')) {
  		add_action('woocommerce_lostpassword_form','rcfwc_field');
  		add_action('lostpassword_post','rcfwc_woo_reset_check', 10, 1);
  		function rcfwc_woo_reset_check($validation_errors) {
  			if(isset($_POST['woocommerce-lost-password-nonce'])) {
  				$check = rcfwc_recaptcha_check();
  				$success = $check['success'];
  				if($success != true) {
  					$validation_errors->add( 'rcfwc_error', __( 'Please complete the reCAPTCHA to verify that you are not a robot.', 'recaptcha-woo' ) );
  				}
  			}
  		}
  	}

  }

}

<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// create custom plugin settings menu
add_action('admin_menu', 'rcfwc_create_menu');
function rcfwc_create_menu() {

	//create new top-level menu
	add_submenu_page( 'options-general.php', 'reCAPTCHA for WooCommerce', 'reCAPTCHA WooCommerce', 'manage_options', __FILE__, 'rcfwc_settings_page' );

	//call register settings function
	add_action( 'admin_init', 'register_rcfwc_settings' );
}

// Register Settings
function register_rcfwc_settings() {
  register_setting( 'rcfwc-settings-group', 'rcfwc_key' );
  register_setting( 'rcfwc-settings-group', 'rcfwc_secret' );
  register_setting( 'rcfwc-settings-group', 'rcfwc_theme' );
  register_setting( 'rcfwc-settings-group', 'rcfwc_login' );
  register_setting( 'rcfwc-settings-group', 'rcfwc_register' );
  register_setting( 'rcfwc-settings-group', 'rcfwc_reset' );
  register_setting( 'rcfwc-settings-group', 'rcfwc_woo_checkout' );
  register_setting( 'rcfwc-settings-group', 'rcfwc_guest_only' );
  register_setting( 'rcfwc-settings-group', 'rcfwc_woo_login' );
  register_setting( 'rcfwc-settings-group', 'rcfwc_woo_register' );
  register_setting( 'rcfwc-settings-group', 'rcfwc_woo_reset' );
  register_setting( 'rcfwc-settings-group', 'rcfwc_selected_payment_methods' );
  register_setting( 'rcfwc-settings-group', 'rcfwc_woo_checkout_pos' );
}

// Keys Updated
add_action('update_option_rcfwc_key', 'rcfwc_keys_updated', 10);
add_action('update_option_rcfwc_secret', 'rcfwc_keys_updated', 10);
function rcfwc_keys_updated() {
	update_option('rcfwc_tested', 'no');
}

/**
 * Enqueue admin scripts
 */
function rcfwc_admin_script_enqueue() {
	wp_register_script("recaptcha", "https://www.google.com/recaptcha/api.js?explicit&hl=" . get_locale());
	wp_enqueue_script("recaptcha");
  }
  add_action( 'admin_enqueue_scripts', 'rcfwc_admin_script_enqueue' );
  
// Admin test form to check reCAPTCHA response
function rcfwc_admin_test() {
	?>
	<form action="" method="POST">
	<?php
	if(!empty(get_option('rcfwc_key')) && !empty(get_option('rcfwc_secret'))) {
		$check = rcfwc_recaptcha_check();
		$success = '';
		$error = '';
		if(isset($check['success'])) $success = $check['success'];
		if(isset($check['error_code'])) $error = $check['error_code'];
		echo '<br/><div style="padding: 20px 20px 25px 20px; background: #fff; border-radius: 20px; max-width: 500px; border: 2px solid #d5d5d5;">';
		if($success != true) {
			echo '<p style="font-weight: 600; font-size: 19px; margin-top: 0; margin-bottom: 0;">' . __( 'Almost done...', 'recaptcha-woo' ) . '</p>';
		}
		if(!isset($_POST['g-recaptcha-response'])) {
			echo '<p>'
			. '<span style="color: red; font-weight: bold;">' . __( 'API keys have been updated. Please test the reCAPTCHA API response below.', 'recaptcha-woo' ) . '</span>'
			. '<br/>'
			. __( 'reCAPTCHA will not be added to WP login until the test is successfully complete.', 'recaptcha-woo' )
			. '</p>';
		} else {
			if($success == true) {
				echo '<p style="font-weight: bold; color: green; margin-top: -2px; margin-bottom: -4px;"><span class="dashicons dashicons-yes-alt"></span> ' . __( 'Success! reCAPTCHA seems to be working correctly with your API keys.', 'recaptcha-woo' ) . '</p>';
				update_option('rcfwc_tested', 'yes');
			} else {
				if($error == "missing-input-response") {
					echo '<p style="font-weight: bold; color: red;">' . esc_html__( 'Please verify that you are human.', 'recaptcha-woo' ) . '</p>';
				} else {
					echo '<p style="font-weight: bold; color: red;">' . esc_html__( 'Failed! There is an error with your API settings. Please check & update them.', 'recaptcha-woo' ) . '<br/>' . esc_html__( 'Error Code:', 'recaptcha-woo' ) . ' ' . $error . '</p>';
				}
			}
			if($error) {
				echo '<p style="font-weight: bold;">' . esc_html__( 'Error Message:', 'recaptcha-woo' ) . " " . esc_html__( 'Please verify that you are human.', 'recaptcha-woo' ) . '</p>';
			}
		}
		if($success != true) {
			echo '<div style="margin-left: 0;">';
			echo rcfwc_field('', '');
			echo '</div><div style="margin-bottom: -20px;"></div>';
			echo '<button type="submit" style="margin-top: 10px; padding: 7px 10px; background: #1c781c; color: #fff; font-size: 15px; font-weight: bold; border: 1px solid #176017; border-radius: 4px; cursor: pointer;">
			'.__( 'TEST RESPONSE', 'recaptcha-woo' ).' <span class="dashicons dashicons-arrow-right-alt"></span>
			</button>';
		}
		echo '</div>';
	}
	?>
	</form>
	<?php
}  

// Show Settings Page
function rcfwc_settings_page() {
?>
<div class="wrap">

<h1><?php echo __( 'reCAPTCHA for WooCommerce', 'recaptcha-woo' ); ?></h1>

<p><?php echo __( 'This plugin will add Google reCAPTCHA to your WooCommerce forms and checkout to help prevent spam.', 'recaptcha-woo' ); ?></p>

<div class="rcfwc-admin-promo-top">
	<p>
		<a href="https://relywp.com/blog/how-to-add-google-recaptcha-to-woocommerce/?utm_source=plugin" title="View our reCAPTCHA plugin setup guide." target="_blank"><?php echo __('View setup guide', 'recaptcha-woo'); ?><span class="dashicons dashicons-external" style="margin-left: 2px; text-decoration: none;"></span></a> &nbsp;&#x2022;&nbsp; <?php echo __('Like this plugin?', 'recaptcha-woo'); ?> <a href="https://wordpress.org/support/plugin/recaptcha-woo/reviews/#new-post" target="_blank" title="<?php echo __('Review on WordPress.org', 'recaptcha-woo'); ?>"><?php echo __('Please submit a review', 'recaptcha-woo'); ?></a> <a href="https://wordpress.org/support/plugin/recaptcha-woo/reviews/#new-post" target="_blank" title="<?php echo __('Review on WordPress.org', 'recaptcha-woo'); ?>" style="text-decoration: none;">
		⭐️⭐️⭐️⭐️⭐️
		</a>
	</p>
</div>

<?php
if(empty(get_option('rcfwc_tested')) || get_option('rcfwc_tested') != 'yes') {
	echo rcfwc_admin_test();
} else {
	echo '<p style="font-weight: bold; color: green; margin-top: 28px;"><span class="dashicons dashicons-yes-alt"></span> ' . __( 'Success! reCAPTCHA seems to be working correctly with your API keys.', 'recaptcha-woo' ) . '</p>';
} ?>

<form method="post" action="options.php">

    <?php settings_fields( 'rcfwc-settings-group' ); ?>
    <?php do_settings_sections( 'rcfwc-settings-group' ); ?>

    <table class="form-table">

    <tr valign="top">
    	<th scope="row" style="padding-bottom: 0;">
    	<p style="font-size: 19px; margin-top: 0;"><?php echo __( 'API Key Settings:', 'recaptcha-woo' ); ?></p>
    	<p style="margin-bottom: 2px;"><?php echo __( 'You can get your site key and secret from here:', 'recaptcha-woo' ); ?> <a href="https://www.google.com/recaptcha/admin/create" target="_blank">https://www.google.com/recaptcha/admin/create</a></p>
    	</th>
    </tr>

    </table>

    <table class="form-table">

        <tr valign="top">
        <th scope="row"><?php echo __( 'Site Key', 'recaptcha-woo' ); ?> (v2)</th>
        <td><input type="text" name="rcfwc_key" value="<?php echo esc_attr( get_option('rcfwc_key') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php echo __( 'Site Secret', 'recaptcha-woo' ); ?> (v2)</th>
        <td><input type="text" name="rcfwc_secret" value="<?php echo esc_attr( get_option('rcfwc_secret') ); ?>" /></td>
        </tr>

		<tr valign="top">
			<th scope="row"><?php echo __( 'reCAPTCHA Theme', 'recaptcha-woo' ); ?></th>
			<td>
				<select name="rcfwc_theme">
					<option value="light"<?php if(!get_option('rcfwc_theme') || get_option('rcfwc_theme') == "light") { ?>selected<?php } ?>>
						<?php esc_html_e( 'Light', 'recaptcha-woo' ); ?>
					</option>
					<option value="dark"<?php if(get_option('rcfwc_theme') == "dark") { ?>selected<?php } ?>>
						<?php esc_html_e( 'Dark', 'recaptcha-woo' ); ?>
					</option>
				</select>
			</td>
		</tr>

    <tr valign="top">
  		<th scope="row" style="padding-bottom: 0;">
  		<p style="font-size: 19px; margin-top: 0; margin-bottom: 0;"><?php echo __( 'WordPress Forms:', 'recaptcha-woo' ); ?></p>
  		</th>
  	</tr>

		<tr valign="top">
			<th scope="row">
			<?php echo __( 'WordPress Login', 'recaptcha-woo' ); ?>
			</th>
			<td><input type="checkbox" name="rcfwc_login" <?php if(get_option('rcfwc_login')) { ?>checked<?php } ?>></td>
		</tr>

		<tr valign="top">
			<th scope="row">
			<?php echo __( 'WordPress Register', 'recaptcha-woo' ); ?>
			</th>
			<td><input type="checkbox" name="rcfwc_register" <?php if(get_option('rcfwc_register')) { ?>checked<?php } ?>></td>
		</tr>

		<tr valign="top">
			<th scope="row">
			<?php echo __( 'Reset Password', 'recaptcha-woo' ); ?>
			</th>
			<td><input type="checkbox" name="rcfwc_woo_reset" <?php if(get_option('rcfwc_woo_reset')) { ?>checked<?php } ?>></td>
		</tr>

  	<tr valign="top">
  		<th scope="row" style="padding-bottom: 0;">
  		<p style="font-size: 19px; margin-top: 0; margin-bottom: 0;"><?php echo __( 'WooCommerce Forms:', 'recaptcha-woo' ); ?></p>
  		</th>
  	</tr>

    <tr valign="top" <?php if ( !class_exists( 'WooCommerce' ) ) { ?>style="opacity: 0.5; pointer-events: none;"<?php } ?>>
			<th scope="row">
			<?php echo __( 'WooCommerce Login', 'recaptcha-woo' ); ?>
			</th>
			<td><input type="checkbox" name="rcfwc_woo_login" <?php if(get_option('rcfwc_woo_login')) { ?>checked<?php } ?>></td>
    </tr>

    <tr valign="top" <?php if ( !class_exists( 'WooCommerce' ) ) { ?>style="opacity: 0.5; pointer-events: none;"<?php } ?>>
			<th scope="row">
			<?php echo __( 'WooCommerce Register', 'recaptcha-woo' ); ?>
			</th>
			<td><input type="checkbox" name="rcfwc_woo_register" <?php if(get_option('rcfwc_woo_register')) { ?>checked<?php } ?>></td>
    </tr>
	
    <tr valign="top" <?php if ( !class_exists( 'WooCommerce' ) ) { ?>style="opacity: 0.5; pointer-events: none;"<?php } ?>>
			<th scope="row">
				<?php echo __( 'WooCommerce Checkout', 'recaptcha-woo' ); ?>
				<br/><br/>
				<?php echo __( 'Guest Checkout Only', 'recaptcha-woo' ); ?>
			</th>
			<td>
				<input type="checkbox" name="rcfwc_woo_checkout" <?php if(get_option('rcfwc_woo_checkout')) { ?>checked<?php } ?>>
				<br/><br/>
				<input type="checkbox" name="rcfwc_guest_only" <?php if(get_option('rcfwc_guest_only')) { ?>checked<?php } ?>>
			</td>
    </tr>

	<tr valign="top" <?php if ( !class_exists( 'WooCommerce' ) ) { ?>style="opacity: 0.5; pointer-events: none;"<?php } ?>>
		<th scope="row" style="padding-top: 0px;">
			<?php echo __( 'Widget Location on Checkout', 'recaptcha-woo' ); ?>
		</th>
		<td style="padding-top: 0px;">
			<select name="rcfwc_woo_checkout_pos">
				<option value="beforepay" <?php if (!get_option('rcfwc_woo_checkout_pos') || get_option('rcfwc_woo_checkout_pos') == "beforepay") { ?>selected<?php } ?>>
					<?php esc_html_e('Before Payment', 'simple-cloudflare-turnstile'); ?>
				</option>
				<option value="afterpay" <?php if (get_option('rcfwc_woo_checkout_pos') == "afterpay") { ?>selected<?php } ?>>
					<?php esc_html_e('After Payment', 'simple-cloudflare-turnstile'); ?>
				</option>
				<option value="beforebilling" <?php if (get_option('rcfwc_woo_checkout_pos') == "beforebilling") { ?>selected<?php } ?>>
					<?php esc_html_e('Before Billing', 'simple-cloudflare-turnstile'); ?>
				</option>
				<option value="afterbilling" <?php if (get_option('rcfwc_woo_checkout_pos') == "afterbilling") { ?>selected<?php } ?>>
					<?php esc_html_e('After Billing', 'simple-cloudflare-turnstile'); ?>
				</option>
			</select>
		</td>
	</tr>

    </table>

	<?php if ( class_exists( 'WooCommerce' ) ) { ?>

		<?php $available_gateways = WC()->payment_gateways->get_available_payment_gateways(); ?>

		<?php if(!empty($available_gateways)) { ?>

		<p style="font-size: 15px; font-weight: 600; margin-top: 0;">
			<?php echo __('Payment Methods to Skip', 'simple-cloudflare-turnstile'); ?>
			<span id="toggleButtonSkipMethods" class="dashicons dashicons-arrow-down" style="cursor:pointer;"></span> <!-- arrow for toggling -->
		</p>

		<div id="toggleContentSkipMethods" style="display: none;"> <!-- Initially hidden -->

			<i style="font-size: 10px;">
			<?php echo __("If selected below, reCAPTCHA check will not be run for that specific payment method.", 'simple-cloudflare-turnstile'); ?>
			<br/>
			<?php echo __("Useful for 'Express Checkout' payment methods compatibility.", 'simple-cloudflare-turnstile'); ?>
			</i>

			<?php
			$selected_payment_methods = get_option('rcfwc_selected_payment_methods', array());
			if(!$selected_payment_methods) $selected_payment_methods = array();
			if(!empty($available_gateways)) { ?>
				<div style="margin-top: 10px; max-width: 200px;">
				<?php foreach ( $available_gateways as $gateway ) : ?>
					<p>
						<input type="checkbox" name="rcfwc_selected_payment_methods[]" style="float: none; margin-top: 2px;"
						value="<?php echo esc_attr( $gateway->id ); ?>" <?php echo in_array( $gateway->id, $selected_payment_methods, true ) ? 'checked' : ''; ?> >
						<label><?php echo __("Skip:", 'simple-cloudflare-turnstile'); ?> <?php echo esc_html( $gateway->get_title() ); ?></label>
					</p>
				<?php endforeach; ?>
				</div>
			<?php } ?>
		</div>

		<script type="text/javascript">
			document.getElementById("toggleButtonSkipMethods").addEventListener("click", function() {
				var content = document.getElementById("toggleContentSkipMethods");
				if (content.style.display === "none") {
					content.style.display = "block"; // Show content
					this.className = "dashicons dashicons-arrow-up"; // Arrow up
				} else {
					content.style.display = "none"; // Hide content
					this.className = "dashicons dashicons-arrow-down"; // Arrow down
				}
			});
		</script>

		<?php } ?>

	<?php } ?>

    <?php submit_button(); ?>

	<br/>

    <div class="rfw-admin-promo">

		<p style="font-size: 15px; font-weight: bold;"><?php echo __( '100% free plugin developed by', 'recaptcha-woo' ); ?> <a href="https://twitter.com/ElliotSowersby" target="_blank" title="@ElliotSowersby on Twitter">Elliot Sowersby</a> (<a href="https://www.relywp.com/?utm_campaign=recaptcha-woo-plugin&utm_source=plugin-settings&utm_medium=promo" target="_blank" title="RelyWP - WordPress Maintenance & Support">RelyWP</a>) 🙌</p>

		<p style="font-size: 15px;">- <?php echo __( 'Find this plugin useful?', 'recaptcha-woo' ); ?> <a href="https://wordpress.org/support/plugin/recaptcha-woo/reviews/#new-post" target="_blank"><?php echo __( 'Please submit a review', 'recaptcha-woo' ); ?></a> <a href="https://wordpress.org/support/plugin/recaptcha-woo/reviews/#new-post" target="_blank" style="text-decoration: none;">⭐️⭐️⭐️⭐️⭐️</a></p>

		<p style="font-size: 15px;">- <?php echo __( 'Need help? Have a suggestion?', 'recaptcha-woo' ); ?> <a href="https://wordpress.org/support/plugin/recaptcha-woo" target="_blank"><?php echo __( 'Create a support topic', 'recaptcha-woo' ); ?><span class="dashicons dashicons-external" style="font-size: 15px; margin-top: 5px; text-decoration: none;"></span></a></p>

		<p style="font-size: 15px;">- <?php echo __( 'Want to support the developer?', 'recaptcha-woo' ); ?> <?php echo __( 'Feel free to', 'recaptcha-woo' ); ?> <a href="https://www.paypal.com/donate/?hosted_button_id=RX28BBH7L5XDS" target="_blank"><?php echo __( 'Donate', 'recaptcha-woo' ); ?><span class="dashicons dashicons-external" style="font-size: 15px; margin-top: 5px; text-decoration: none;"></span></a></p>

		<br/>

		<p style="font-size: 12px;">
			
			<a href="https://translate.wordpress.org/projects/wp-plugins/recaptcha-woo/" target="_blank"><?php echo __( 'Translate into your language', 'recaptcha-woo' ); ?><span class="dashicons dashicons-external" style="font-size: 15px; margin-top: 2px; text-decoration: none;"></span></a>
			
			<br/>
			
			<a href="https://github.com/elliotsowersby/recaptcha-woo" target="_blank"><?php echo __( 'View on GitHub', 'recaptcha-woo' ); ?><span class="dashicons dashicons-external" style="font-size: 15px; margin-top: 2px; text-decoration: none;"></span></a>
		
		</p>

    </div>

	<br/>

    <div class="rfw-admin-promo">

		<p style="font-size: 15px; font-weight: bold;"><?php echo __( 'Check out our other plugins:', 'recaptcha-woo' ); ?></p>

		<p style="font-size: 15px;"><a href="https://couponaffiliates.com/?utm_campaign=recaptcha-woo-plugin&utm_source=plugin-settings&utm_medium=promo" target="_blank"><?php echo __( 'Coupon Affiliates for WooCommerce', 'recaptcha-woo' ); ?></a></p>

		<p style="font-size: 15px;"><a href="https://relywp.com/plugins/tax-exemption-woocommerce/?utm_campaign=recaptcha-woo-plugin&utm_source=plugin-settings&utm_medium=promo" target="_blank"><?php echo __( 'Tax Exemption for WooCommerce', 'recaptcha-woo' ); ?></a></p>

	<br/>

	<br/><br/>

</form>
</div>

<?php } ?>
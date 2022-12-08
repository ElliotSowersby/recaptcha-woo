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
			'.__( 'TEST API RESPONSE', 'recaptcha-woo' ).' <span class="dashicons dashicons-arrow-right-alt"></span>
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

<p style="font-size: 14px; background: #fff; padding: 10px; display: inline-block; border: 1px solid #333; border-radius: 4px; margin-bottom: 0;">
  <strong style="color: green;"><?php echo __( '[NEW]', 'recaptcha-woo' ); ?></strong>
  <?php echo __( '<a href="https://www.cloudflare.com/en-gb/products/turnstile/" target="_blank">Cloudflare Turnstile</a> is a new user-friendly, privacy-preserving, reCAPTCHA alternative!', 'recaptcha-woo' ); ?>
  <br/>
  <?php echo __( 'You can switch to this now with our new 100% free plugin:', 'recaptcha-woo' ); ?> <a href="<?php echo get_admin_url(); ?>plugin-install.php?s=Simple%20Cloudflare%20Turnstile%20RelyWP&tab=search&type=term" target="_blank">Simple Cloudflare Turnstile<span class="dashicons dashicons-external" style="height: 15px; font-size: 15px; margin-top: 2px; text-decoration: none;"></span></a>
</p>

<br/><br/>

<h1><?php echo __( 'reCAPTCHA for WooCommerce', 'recaptcha-woo' ); ?></h1>

<p><?php echo __( 'This plugin will add Google reCAPTCHA to your WooCommerce checkout and forms to help prevent spam.', 'recaptcha-woo' ); ?></p>

<?php
if(empty(get_option('rcfwc_tested')) || get_option('rcfwc_tested') != 'yes') {
	echo rcfwc_admin_test();
} else {
	echo '<p style="font-weight: bold; color: green;"><span class="dashicons dashicons-yes-alt"></span> ' . __( 'Success! reCAPTCHA seems to be working correctly with your API keys.', 'simple-cloudflare-turnstile' ) . '</p>';
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
			<?php echo __( 'WordPress Login', 'simple-cloudflare-turnstile' ); ?>
			</th>
			<td><input type="checkbox" name="rcfwc_login" <?php if(get_option('rcfwc_login')) { ?>checked<?php } ?>></td>
		</tr>

		<tr valign="top">
			<th scope="row">
			<?php echo __( 'WordPress Register', 'simple-cloudflare-turnstile' ); ?>
			</th>
			<td><input type="checkbox" name="rcfwc_register" <?php if(get_option('rcfwc_register')) { ?>checked<?php } ?>></td>
		</tr>

		<tr valign="top">
			<th scope="row">
			<?php echo __( 'Reset Password', 'simple-cloudflare-turnstile' ); ?>
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

    </table>

    <?php submit_button(); ?>

    <div class="rfw-admin-promo">

    <p style="font-size: 15px; font-weight: bold;"><?php echo __( '100% free plugin developed by', 'recaptcha-woo' ); ?> <a href="https://twitter.com/ElliotVS" target="_blank" title="@ElliotVS on Twitter">Elliot Sowersby</a> (<a href="https://www.relywp.com/?utm_source=rfw" target="_blank" title="RelyWP - WordPress Maintenance & Support">RelyWP</a>) 🙌</p>

    <p style="font-size: 15px;">- <?php echo __( 'Find this plugin useful?', 'recaptcha-woo' ); ?> <a href="https://wordpress.org/support/plugin/recaptcha-woo/reviews/#new-post" target="_blank"><?php echo __( 'Please submit a review', 'recaptcha-woo' ); ?></a> <a href="https://wordpress.org/support/plugin/recaptcha-woo/reviews/#new-post" target="_blank" style="text-decoration: none;">⭐️⭐️⭐️⭐️⭐️</a></p>

    <p style="font-size: 15px;">- <?php echo __( 'Need help? Have a suggestion?', 'recaptcha-woo' ); ?> <a href="https://wordpress.org/support/plugin/recaptcha-woo" target="_blank"><?php echo __( 'Create a support topic', 'recaptcha-woo' ); ?><span class="dashicons dashicons-external" style="font-size: 15px; margin-top: 5px; text-decoration: none;"></span></a></p>

	<p style="font-size: 15px;">- <?php echo __( 'Want to support the developer?', 'simple-cloudflare-turnstile' ); ?> <?php echo __( 'Feel free to', 'simple-cloudflare-turnstile' ); ?> <a href="https://www.paypal.com/donate/?hosted_button_id=RX28BBH7L5XDS" target="_blank"><?php echo __( 'Donate', 'simple-cloudflare-turnstile' ); ?><span class="dashicons dashicons-external" style="font-size: 15px; margin-top: 5px; text-decoration: none;"></span></a></p>

    <br/>

    <p style="font-size: 12px;">
		<a href="https://translate.wordpress.org/projects/wp-plugins/recaptcha-woo/" target="_blank"><?php echo __( 'Translate into your language', 'recaptcha-woo' ); ?><span class="dashicons dashicons-external" style="font-size: 15px; margin-top: 2px; text-decoration: none;"></span></a>
		<br/>
		<a href="https://github.com/elliotvs/recaptcha-woo" target="_blank"><?php echo __( 'View on GitHub', 'simple-cloudflare-turnstile' ); ?><span class="dashicons dashicons-external" style="font-size: 15px; margin-top: 2px; text-decoration: none;"></span></a>
	</p>

    </div>
</form>
</div>

<?php } ?>
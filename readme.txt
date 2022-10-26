=== reCAPTCHA for WooCommerce ===
Contributors: ElliotVS, RelyWP
Tags: recaptcha,woocommerce,checkout,spam,protect
Donate link: https://www.paypal.com/donate/?hosted_button_id=RX28BBH7L5XDS
Requires at least: 4.7
Tested up to: 6.0.2
Stable Tag: 1.2.0
License: GPLv3 or later.
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Add Google reCAPTCHA to your WooCommerce Checkout, Login, and Registration Forms.

== Description ==

Easily add Google reCAPTCHA to WooCommerce checkout and forms to help prevent spam.

## Supported Forms ##

You can currently enable the reCAPTCHA on the following forms:

**WooCommerce**

* Checkout
* Login Form
* Registration Form
* Password Reset Form

**WordPress**

* Login Form
* Registration Form
* Password Reset Form

## Getting Started ##

Simply generate your Google reCAPTCHA v2 site "key" and "secret" and add these to the settings.

Choose which forms you want it to show on, and set the theme to either dark or light.

A new reCAPTCHA WooCommerce field will then be displayed on your checkout, and other selected forms to protect them from spam!

## Localisation ##

The language for the WooCommerce reCAPTCHA will be automatically set based on your sites default language.

## Is it free to use? ##

Yes, this plugin is completely free with no paid version, and it doesn't track your data. Google reCAPTCHA is also a completely free service!

== Installation ==

1. Upload 'recaptcha-woo' to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Customise plugin settings in WordPress admin at Settings > reCAPTCHA WooCommerce

== Screenshots ==

1. Example reCAPTCHA on the My Account Page
2. Example reCAPTCHA on the Checkout Page

== Changelog ==

= Version 1.2.0 - 27th October 2022 =
- New: Added options to enable reCAPTCHA on the WP Login and WP Register page (wp-login.php).
- New: Added a new "Test API Response" step to the settings page, whenever the API keys are updated to make sure it's working. reCAPTCHA will not work on your WP login form until the test is successfully complete.
- Tweak: A few small changes to the admin settings page.
- Other: Tested with WooCommerce 7.0.0

= Version 1.1.3 - 23rd October 2022 =
- Fix: Fixed issue with Turnstile verification not working correctly on checkout if "Create an account?" was selected.

= Version 1.1.2 - 11th October 2022 =
- New: Added language detection for the captcha, so it will now show in the "Site Language" instead of just English.

= Version 1.1.1 - 10th October 2022 =
- Fix: Fixed issue with reCAPTCHA not loading on checkout in some cases since last update.

= Version 1.1.0 - 10th October 2022 =
- New: Enable reCAPTCHA on WooCommerce Login, Register and Password Reset forms.
- Other: Tested with WooCommerce 6.9.4

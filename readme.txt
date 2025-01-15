=== reCAPTCHA for WooCommerce ===
Contributors: ElliotVS, RelyWP
Tags: recaptcha,woocommerce,checkout,spam,protect
Donate link: https://www.paypal.com/donate/?hosted_button_id=RX28BBH7L5XDS
Requires at least: 4.7
Tested up to: 6.7.1
Stable Tag: 1.4.1
License: GPLv3 or later.
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Add Google reCAPTCHA to your WooCommerce Checkout, Login, and Registration Forms.

== Description ==

Easily add Google reCAPTCHA to WooCommerce checkout and forms to help prevent spam.

## Supported Forms ##

You can currently enable the reCAPTCHA on the following forms:

**WooCommerce**

* Checkout (Shortcode & Block)
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

Yes, this plugin is completely free with no paid version.

Google reCAPTCHA is also a completely free service. You can view their privacy policy <a href="https://policies.google.com/privacy">here</a> and terms and conditions <a href="https://policies.google.com/terms">here</a>.

Please consider helping out by <a href="https://wordpress.org/support/plugin/recaptcha-woo/reviews/#new-post">leaving a review</a>, or <a href="https://www.paypal.com/donate/?hosted_button_id=RX28BBH7L5XDS">donate</a>.

## Alternative Plugin ##

Want a user-friendly, privacy-preserving reCAPTCHA alternative? Check out the <a href="https://wordpress.org/plugins/simple-cloudflare-turnstile/">Simple Cloudflare Turnstile</a> plugin instead (100% free).

== Installation ==

1. Upload 'recaptcha-woo' to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Customise plugin settings in WordPress admin at Settings > reCAPTCHA WooCommerce

== Screenshots ==

1. Example reCAPTCHA on the My Account Page
2. Example reCAPTCHA on the Checkout Page

== Changelog ==

= Version 1.4.1 - 15th January 2025 =
- Fix: Fixed an issue with saving the "Load scripts on all pages?" option.

= Version 1.4.0 - 17th December 2024 =
- New: Added Checkout block and Store API support. (Thanks [@senadir](https://github.com/senadir))
- Fix: Fixed an issue with reCAPTCHA re-rendering when it does not need to in some cases, on WooCommerce checkout.
- Other: Tested with WooCommerce 9.4.3

= Version 1.3.6 - 29th November 2024 =
- Fix: Fixed an issue with saving the "Load scripts on all pages?" option.

= Version 1.3.5 - 22nd November 2024 =
- Tweak: Added option to decide where scripts should be loaded pages other than My Account and Checkout.

= Version 1.3.4 - 22nd November 2024 =
- Fix: Fixed the scripts being loaded on other pages when not needed.
- Other: Tested with WordPress 6.7.1
- Other: Tested with WooCommerce 9.4.2

= Version 1.3.3 - 15th February 2024 =
- Tweak: Set the loading of JS file and reCAPTCHA script to be deferred.
- Tweak: A few small changes to the admin settings page.
- Tweak: Added skip REST API for the registration form check.
- Other: Tested with WordPress 6.4.3
- Other: Tested with WooCommerce 8.5.2

= Version 1.3.2 - 26th October 2023 =
- Tweak: Added function to declare comaptibility with with HPOS.
- Fix: Fixed issue with reCAPTCHA not resetting on checkout if there is an error submitting checkout.
- Fix: Fixed a "grecaptcha.reset is not a function" javascript console error that could occur on the checkout page.
- Other: Tested with WordPress 6.4.0

= Version 1.3.1 - 26th July 2023 =
- Tweak: Modified the "Payment Methods to Skip" option (for WooCommerce) information to be easier to understand, and now displayed as checkboxes instead of a multi-select field.

= Version 1.3.0 - 18th June 2023 =
- New: Added option to skip reCAPTCHA check for selected WooCommerce payment methods. Useful for Express Checkout options.
- New: Added option to select the location of the reCAPTCHA on the checkout page.
- Tweak: Update to make compatible with certain other login security plugins, or any other plugins that run the login "authenticate" multiple times.
- Tweak: Added skip for REST API and XMLRPC on WordPress login check.
- Tweak: Changing the keys will require the TEST API RESPONSE to be run again.
- Tweak: Made a few small tweaks to the admin settings page.
- Other: Tested with WordPress 6.2.2
- Other: Tested with WooCommerce 7.8.0

= Version 1.2.10 - 27th April 2023 =
- Tweak: Edit to admin settings page.
- Other: Tested with WordPress 6.2
- Other: Tested with WooCommerce 7.6.1

= Version 1.2.9 - 12th January 2023 =
- Tweak: reCAPTCHA on WordPress login will now work better with plugins that hide/change the admin login URL.
- Tweak: WordPress Login and Register will skip Turnstile check for XMLRPC requests.
- Tweak: reCAPTCHA will now re-render properly when there is an error on the checkout page.
- Tweak: Edited the filter used for WordPress login authentication.
- Other: Tested with WooCommerce 7.3.0

= Version 1.2.8 - 8th December 2022 =
- Tweak: Update to the code changes made in 1.2.7.

= Version 1.2.7 - 8th December 2022 =
- Fix: Fixed issue with some websites not being able to complete the "Test API Response" step in settings.
- Other: Tested with WooCommerce 7.1.1

= Version 1.2.6 - 7th December 2022 =
- Fix: Fixed issue with reCAPTCHA still showing on checkout even if it was toggled off in settings.

= Version 1.2.5 - 23rd November 2022 =
- Tweak: A few small changes to the admin settings page.
- Fix: Fix the "WooCommerce Checkout" checkbox in settings not showing as unchecked when disabled.

= Version 1.2.4 - 16th November 2022 =
- Fix: Fixed issue with reCAPTCHA sometimes no longer showing on checkout.
- Fix: Fixed redirect to settings page on activate.
- Other: Tested with WordPress 6.1.1
- Other: Tested with WooCommerce 7.1.0

= Version 1.2.3 - 12th November 2022 =
- Fix: Added check to see if WooCommerce is activated and not display error if it is not.

= Version 1.2.2 - 6th November 2022 =
- Tweak: Upon submitting checkout form, if there is an error, it will now automatically reset the challenge.

= Version 1.2.1 - 2nd November 2022 =
- Fix: Fixed bug on admin settings page.
- Other: Tested with WordPress 6.1.0

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
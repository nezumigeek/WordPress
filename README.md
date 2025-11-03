# NezumiGeek README

## Overview

This repository contains the files for the **WordPress web site**.

## Breakdown Log

### UpdateDisable.php:

head to Code Snippets » Library from your WordPress admin dashboard.

Then, search for the ‘Disable Automatic Updates’ snippet and click on the ‘Use snippet’ button.

Choose the Disable Automatic Updates snippet from WPCode library
WPCode will then automatically add the code snippet and select the proper insertion method.

The code snippet has three filters to disable WordPress core updates, plugin updates, and theme updates.

WPCode's Disable Automatic Updates snippet
If you don’t want to use one of these filters, simply edit the code to add an //at the beginning of the filter line.

For example, adding an // to the core auto-updates filter line will prevent it from executing. So, you’ll still get automatic updates for the core, but not for plugins and themes.

Edit the Disable Automatic Update filters
After that, all you need to do is toggle the switch from ‘Inactive’ to ‘Active.’

Then, click on the ‘Update’ button.

Activate and update snippet in WPCode
That’s it. You’ve now disabled automatic updates in WordPress.

### couponUrl.php: (My Priority 1)
This code enables automatic coupon application via a URL parameter. If a user visits a link like ?apply_coupon=SUMMERDEAL, WooCommerce will apply that coupon to their cart—unless it's already applied. It’s a handy way to run promotions or share discount links.

// Hook into the "init" action
This comment explains that the code is hooking into WordPress’s init action, which runs after WordPress has finished loading but before any headers are sent.

add_action('init', 'woosuite_auto_apply_coupon_code_from_url');
Registers the function woosuite_auto_apply_coupon_code_from_url to run during the init phase.

This ensures the function executes early in the page lifecycle, allowing coupon logic to be processed before the cart is displayed.

function woosuite_auto_apply_coupon_code_from_url() {
Begins the definition of the function that will handle the coupon logic.

$coupon_code = 'CrazySale';
Sets a default coupon code (CrazySale) in case no code is passed via the URL.

if (isset($_GET['apply_coupon'])) {
Checks if the URL contains a query parameter named apply_coupon.

Example: https://example.com/?apply_coupon=SUMMERDEAL

$coupon_code = sanitize_text_field($_GET['apply_coupon']);
Retrieves the coupon code from the URL and sanitizes it to prevent malicious input (e.g., XSS attacks).

if (WC()->cart->has_discount($coupon_code)) {
Checks if the coupon is already applied to the cart.

Prevents duplicate application.

return; // Coupon is already applied
If the coupon is already in use, the function exits early and does nothing further.

WC()->cart->apply_coupon($coupon_code);
Applies the coupon to the WooCommerce cart.

This triggers WooCommerce’s internal logic to validate and apply the discount.

} (closing braces)
Ends the conditional blocks and the function.

### Security.php: (Priority 1)
  - global $user_ID;
Declares $user_ID as a global variable, making it accessible within the current scope.

$user_ID typically holds the ID of the currently logged-in WordPress user.

if($user_ID) {
Checks if a user is logged in (i.e., $user_ID is not null or zero).

If no user is logged in, the rest of the code is skipped.

if(!current_user_can('administrator')) {
Checks if the logged-in user does not have administrator privileges.

Only non-admin users will be subject to the following URI checks.

if (strlen($_SERVER['REQUEST_URI']) > 255 || ... ) {
Begins a conditional block that checks for suspicious patterns in the request URI.

The conditions include:

strlen($_SERVER['REQUEST_URI']) > 255: URI is excessively long, which could indicate an attempt to exploit buffer overflows or inject malicious code.

stripos($_SERVER['REQUEST_URI'], "eval("): Checks for use of eval(, often used in malicious scripts.

stripos($_SERVER['REQUEST_URI'], "CONCAT"): Detects SQL injection attempts using the CONCAT function.

stripos($_SERVER['REQUEST_URI'], "UNION+SELECT"): Detects SQL injection attempts using UNION SELECT.

stripos($_SERVER['REQUEST_URI'], "base64"): Flags potential obfuscation using base64 encoding.

@header("HTTP/1.1 414 Request-URI Too Long");
Sends an HTTP 414 status code, indicating the URI is too long or malformed.

The @ suppresses any errors that might occur when sending headers.

@header("Status: 414 Request-URI Too Long");
Sends a status header for compatibility with certain server configurations.

@header("Connection: Close");
Instructs the server to close the connection after sending the response.

@exit;
Terminates script execution immediately, preventing further processing of the request.

## Change Log

| Date       | Version      | Changes                                                                                                    |
|------------|--------------|------------------------------------------------------------------------------------------------------------|
| 1x/x/25   | V0.0.2      | - README.md - Added Readme.<br> - couponUrl.php - unknown xxx                               |
| 11/3/25   | V0.0.1      | - README.md - Added Readme <br> - couponUrl.php - Added initial Code. <br> security.php - Added initial code. <br> updateDisable.php - Added initial code.                                  |


## Additional Notes

None, yet
# NezumiGeek README

![WordPress](https://img.shields.io/badge/WordPress-6.8.3-blue)
![PHP](https://img.shields.io/badge/PHP-8.2.29-green)
![MySQL](https://img.shields.io/badge/MySQL-8.0.42-blue)
![Last Updated](https://img.shields.io/badge/Updated-November%204%2C%202025-blue)
![Status](https://img.shields.io/badge/Status-Active-brightgreen)
![License](https://img.shields.io/badge/License-Unspecified-lightgrey)

## Overview

This repository contains the files for the **WordPress web site**.

## Installation & Code Overview

### Install method for security.php, couponUrl.php, and wholesaleRemoveSub.php:
Go to Code Snippets » Library in your WordPress admin dashboard.

Click “Add New” or “Add Custom Snippet.”

Paste the PHP code from the file (e.g., security.php).

Set the “Run snippet everywhere” option.

Click “Save Changes and Activate” (or just “Enable” if already saved).

### Install method for UpdateDisable.php:

Code Snippets » Library from the WordPress admin dashboard.

Search for ‘Disable Automatic Updates’ snippet and click the ‘Use snippet’ button.

Choose the Disable Automatic Updates snippet from WPCode library
WPCode "should" automatically add the code snippet and select the proper insertion method.

The code snippet has three filters to disable WordPress core updates, plugin updates, and theme updates.

Edit the Disable Automatic Update filters
After that, all you need to do is toggle the switch from ‘Inactive’ to ‘Active.’

Then, click on the ‘Update’ button.

Activate and update snippet in WPCode
That’s it. You’ve disabled automatic updates in WordPress.

Note: To Disable a portion of the Automatic Updates snippet
If you don’t want to use one of these filters, simply edit the code to add an // at the beginning of the filter line. For example, adding // to the core auto-updates filter line will prevent it from executing. So, you’ll still get automatic updates for the core, but not for plugins and themes.

### Install method for holiday.html and holiday.css (Icegram Engage)
Icegram Engage » Add New Campaign from the WordPress admin dashboard.

Click “Add New Campaign” and enter a descriptive title (e.g., “Holiday Promo Popup”).

In the Message Body section, pasted the contents of holiday.html.

Scrolled down to the Custom CSS field and paste the contents of holiday.css.

Reviewed campaign settings:
Set display rules (e.g., show sitewide, after delay, always, All Devices, All users, and Current session for campaign).

Clicked “Update” to save and activate the campaign.

Note: If the background image does not appear or scales incorrectly, verify that:
The image URL in holiday.css is correct and publicly accessible.
The CSS includes background-size: cover or contain as needed.
The popup container has overflow: hidden and position: relative to support layering and rounded corners.

#### wholesaleRemoveSub.php
This code ensures that wholesale products do not show subscription pricing or options on their product pages.

add_action('wp', 'remove_subscription_price_for_wholesale_products');
Places the custom function remove_subscription_price_for_wholesale_products into the 'wp' action. What 'wp' does: This hook fires once WordPress is fully loaded but before any headers are sent. It’s a good place to run logic that depends on WordPress being initialized.

function remove_subscription_price_for_wholesale_products() { ... }
Defines the function that will be executed when the 'wp' action is triggered. This function checks if the current page is a product and whether that product belongs to the "wholesale" category.

if (is_product() && has_term('wholesale', 'product_cat')) {
is_product(): Checks if the current page is a single product page.
has_term('wholesale', 'product_cat'): Checks if the product belongs to the category with the slug 'wholesale'.
Combined logic: Only proceed if the current page is a product and that product is categorized as wholesale.

remove_action('woocommerce_single_product_summary', 'WCS_ATT_Product_Prices::display_subscription_price', 10);
Removes the display of subscription pricing from the product summary.
Target: The method WCS_ATT_Product_Prices::display_subscription_price is part of the WooCommerce Subscriptions plugin or an add-on.
Priority 10: Matches the priority at which the subscription price is normally added, ensuring it’s removed correctly.

remove_action('woocommerce_single_product_summary', 'WCS_ATT_Display::render_subscription_options', 25);
Removes the subscription options UI (like dropdowns or radio buttons) from the product summary.
Target: The method WCS_ATT_Display::render_subscription_options handles rendering those options.
Priority 25: Ensures it removes the action at the correct point in the WooCommerce product summary flow.

#### couponUrl.php:
This code enables automatic coupon application via a URL parameter. If a user visits a link like ?apply_coupon=SUMMERDEAL, WooCommerce will apply that coupon to their cart—unless a coupon is already applied.

// Hooks into the "init" action
The code is hooking into WordPress’s init action, which runs after WordPress has finished loading but before any headers are sent.

add_action('init', 'woosuite_auto_apply_coupon_code_from_url');
Registers the function woosuite_auto_apply_coupon_code_from_url to run during the init phase.

This ensures the function executes early in the page refresh, allowing coupon logic to be processed before the cart is fully displayed.

function woosuite_auto_apply_coupon_code_from_url() {
Begins the definition of the function that will handle the coupon logic.

$coupon_code = 'CrazySale';
Sets a default coupon code (CrazySale) in case no code is passed via the URL. Can leave blank '' for no coupon.

if (isset($_GET['apply_coupon'])) {
Checks if the URL contains a query parameter named apply_coupon.

Example: https://example.com/?apply_coupon=SUMMERDEAL

$coupon_code = sanitize_text_field($_GET['apply_coupon']);
Retrieves the coupon code from the URL and sanitizes it to prevent malicious input (e.g., XSS attacks).

if (WC()->cart->has_discount($coupon_code)) {
Checks if the coupon is already applied to the cart and prevents duplicate application.

return; // Coupon is already applied
If the coupon is already in use, the function exits early and does nothing further.

WC()->cart->apply_coupon($coupon_code);
Applies the coupon to the WooCommerce cart. This triggers WooCommerce’s internal logic to validate and apply the discount.


#### security.php:

global $user_ID;
Declares $user_ID as a global variable, making it accessible within the current scope. $user_ID typically holds the ID of the currently logged-in WordPress user.

if($user_ID) {
Checks if a user is logged in (Actually, it checks that the $user_ID variable is not null or zero). If no user is logged in, the rest of the code is skipped.

if(!current_user_can('administrator')) {
Checks if the logged-in user does not have administrator privileges. Only non-admin users will be subject to the following URI checks.

if (strlen($_SERVER['REQUEST_URI']) > 255 || ... ) {
Begins a conditional block that checks for suspicious patterns in the request URI.

The conditions include:

strlen($_SERVER['REQUEST_URI']) > 255: URI is excessively long, which could indicate an attempt to exploit buffer overflows or inject malicious code.

stripos($_SERVER['REQUEST_URI'], "eval("): Checks for use of eval(, often used in malicious scripts.

stripos($_SERVER['REQUEST_URI'], "CONCAT"): Detects SQL injection attempts using the CONCAT function.

stripos($_SERVER['REQUEST_URI'], "UNION+SELECT"): Detects SQL injection attempts using UNION SELECT.

stripos($_SERVER['REQUEST_URI'], "base64"): Flags potential obfuscation using base64 encoding.

@header("HTTP/1.1 414 Request-URI Too Long");
Sends an HTTP 414 status code, indicating the URI is too long or malformed. The @ suppresses any errors that might occur when sending headers.

@header("Status: 414 Request-URI Too Long");
Sends a status header for compatibility with certain server configurations.

@header("Connection: Close");
Instructs the server to close the connection after sending the response.

@exit;
Terminates script execution immediately, preventing further processing of the request.

## Change Log

| Date       | Version      | Changes                                                                                                    |
|------------|--------------|------------------------------------------------------------------------------------------------------------|
| 11/5/25   | V0.0.3      | - Added holiday popup campaign via Icegram Engage plugin.<br> - Created and embedded custom HTML (holiday.html) and CSS (holiday.css) into campaign message and styling fields.<br> - Configured popup display settings and verified responsive behavior across screen sizes.<br> - Applied green-themed typography, black CTA button, white background, and rounded corners with black border.<br> - Adjusted background image layering to preserve visibility and positioning at bottom-left.<br> - Updated README.md with installation instructions for Icegram Engage popup integration.                                 |
| 11/4/25  | V0.0.2  | - README.md: Improved formatting and clarified installation steps.<br> - couponUrl.php: Documented default coupon logic.<br> - wholesaleRemoveSub.php: Added initial code and explanation for WooCommerce hook usage. |
| 11/3/25   | V0.0.1      | - README.md - Added Readme <br> - couponUrl.php - Added initial Code. <br> security.php - Added initial code. <br> updateDisable.php - Added initial code.                                  |


## Additional Notes

None, yet
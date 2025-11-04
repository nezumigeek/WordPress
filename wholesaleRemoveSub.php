add_action('wp', 'remove_subscription_price_for_wholesale_products');
function remove_subscription_price_for_wholesale_products() {
    if (is_product() && has_term('wholesale', 'product_cat')) { // Replaced 'wholesale' with our wholesale category slug
        // Hide subscription price and options for wholesale products
        remove_action('woocommerce_single_product_summary', 'WCS_ATT_Product_Prices::display_subscription_price', 10);
        remove_action('woocommerce_single_product_summary', 'WCS_ATT_Display::render_subscription_options', 25); // Hide subscription options
    }
}
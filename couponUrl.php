// Hook into the "init" action
add_action('init', 'woosuite_auto_apply_coupon_code_from_url');

function woosuite_auto_apply_coupon_code_from_url() {
	$coupon_code = 'CrazySale';
    if (isset($_GET['apply_coupon'])) {
        $coupon_code = sanitize_text_field($_GET['apply_coupon']);

        // Check if the coupon code is valid
        if (WC()->cart->has_discount($coupon_code)) {
            return; // Coupon is already applied
        }

        // Apply the coupon
        WC()->cart->apply_coupon($coupon_code);
    }
}
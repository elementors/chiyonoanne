<?php

if (!defined('ABSPATH')) { exit; }

if (!class_exists('DHLPWC_Model_Logic_Access_Control')) :

class DHLPWC_Model_Logic_Access_Control extends DHLPWC_Model_Core_Singleton_Abstract
{

    public function check_enabled()
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        if (empty($shipping_method)) {
            return false;
        }

        if (!isset($shipping_method['enable_all'])) {
            return false;
        }

        if ($shipping_method['enable_all'] != 'yes') {
            return false;
        }

        return true;
    }

    public function check_submenu_link()
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        if (empty($shipping_method)) {
            return false;
        }

        if (!isset($shipping_method['enable_submenu_link'])) {
            return false;
        }

        if ($shipping_method['enable_submenu_link'] != 'yes') {
            return false;
        }

        return true;
    }

    public function check_application_country()
    {
        $country_code = wc_get_base_location();

        if (!in_array($country_code['country'], array(
            'NL',
            'BE',
            'LU',
            'CH',
        ))) {
            return false;
        }

        return true;
    }

    public function check_account()
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        if (empty($shipping_method)) {
            return false;
        }

        if (empty($shipping_method['user_id'])) {
            return false;
        }

        if (empty($shipping_method['key'])) {
            return false;
        }

        if (empty($shipping_method['account_id'])) {
            return false;
        }

        if (empty($shipping_method['organization_id'])) {
            return false;
        }

        return true;
    }

    public function check_default_shipping_address()
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        if (!empty($shipping_method)) {
            return false;
        }

        if (empty($shipping_method['first_name'])) {
            return false;
        }

        if (empty($shipping_method['last_name'])) {
            return false;
        }

        if (empty($shipping_method['company'])) {
            return false;
        }

        if (empty($shipping_method['country'])) {
            return false;
        }

        if (empty($shipping_method['postcode'])) {
            return false;
        }

        if (empty($shipping_method['city'])) {
            return false;
        }

        if (empty($shipping_method['street'])) {
            return false;
        }

        if (empty($shipping_method['number'])) {
            return false;
        }

        if (empty($shipping_method['email'])) {
            return false;
        }

        if (empty($shipping_method['phone'])) {
            return false;
        }

        return true;

    }

    public function check_column_info()
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        if (empty($shipping_method)) {
            return false;
        }

        if (!isset($shipping_method['enable_column_info'])) {
            return false;
        }

        if ($shipping_method['enable_column_info'] != 'yes') {
            return false;
        }

        return true;
    }

    public function check_open_label_links_external()
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        if (empty($shipping_method)) {
            return false;
        }

        if (!isset($shipping_method['open_label_links_external'])) {
            return false;
        }

        if ($shipping_method['open_label_links_external'] != 'yes') {
            return false;
        }

        return true;
    }

    public function check_bulk_create()
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        if (empty($shipping_method)) {
            return false;
        }

        $bulk_options = array(
            'bp_only',
            'smallest',
            'small_only',
            'medium_only',
            'large_only',
            'xsmall_only',
            'xlarge_only',
            'largest'
        );

        $enabled = array();

        foreach($bulk_options as $bulk_option) {
            if (isset($shipping_method['enable_bulk_option_'.$bulk_option]) && $shipping_method['enable_bulk_option_'.$bulk_option] == 'yes') {
                $enabled[] = $bulk_option;
            }
        }

        if (!count($enabled)) {
            return null;
        }

        return $enabled;
    }

    public function check_bulk_print()
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        if (empty($shipping_method)) {
            return false;
        }

        if (!isset($shipping_method['bulk_label_printing'])) {
            return false;
        }

        if ($shipping_method['bulk_label_printing'] != 'yes') {
            return false;
        }

        return true;
    }

    public function check_track_trace_mail()
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        if (empty($shipping_method)) {
            return false;
        }

        if (!isset($shipping_method['enable_track_trace_mail'])) {
            return false;
        }

        if ($shipping_method['enable_track_trace_mail'] != 'yes') {
            return false;
        }

        return true;
    }

    public function check_track_trace_component()
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        if (empty($shipping_method)) {
            return false;
        }

        if (!isset($shipping_method['enable_track_trace_component'])) {
            return false;
        }

        if ($shipping_method['enable_track_trace_component'] != 'yes') {
            return false;
        }

        return true;
    }

    public function check_debug()
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        if (empty($shipping_method)) {
            return false;
        }

        if (!isset($shipping_method['enable_debug'])) {
            return false;
        }

        if ($shipping_method['enable_debug'] != 'yes') {
            return false;
        }

        if (empty($shipping_method['debug_url'])) {
            return false;
        }

        if (filter_var($shipping_method['debug_url'], FILTER_VALIDATE_URL) === false) {
            return false;
        }

        return $shipping_method['debug_url'];
    }

    public function check_debug_external()
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        if (empty($shipping_method)) {
            return false;
        }

        if (!isset($shipping_method['enable_debug'])) {
            return false;
        }

        if ($shipping_method['enable_debug'] != 'yes') {
            return false;
        }

        if (empty($shipping_method['debug_external_url'])) {
            return false;
        }

        if (filter_var($shipping_method['debug_external_url'], FILTER_VALIDATE_URL) === false) {
            return false;
        }

        return $shipping_method['debug_external_url'];
    }

    public function check_debug_mail()
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        if (empty($shipping_method)) {
            return false;
        }

        if (!isset($shipping_method['enable_debug'])) {
            return false;
        }

        if ($shipping_method['enable_debug'] != 'yes') {
            return false;
        }

        if (!isset($shipping_method['enable_debug_mail'])) {
            return false;
        }

        if ($shipping_method['enable_debug_mail'] != 'yes') {
            return false;
        }

        return true;
    }

    public function check_default_send_to_business()
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        if (empty($shipping_method)) {
            return false;
        }

        if (!isset($shipping_method['default_send_to_business'])) {
            return false;
        }

        if ($shipping_method['default_send_to_business'] != 'yes') {
            return false;
        }

        return true;
    }

    public function check_default_send_signature()
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        if (empty($shipping_method)) {
            return false;
        }

        if (!isset($shipping_method['check_default_send_signature'])) {
            return false;
        }

        if ($shipping_method['check_default_send_signature'] != 'yes') {
            return false;
        }

        return true;
    }

    public function check_shipping_preset_enabled($code)
    {
        if (!is_string($code)) {
            return false;
        }

        if (!in_array($code, array(
            'home',
            'evening',
            'same_day',
            'no_neighbour',
            'no_neighbour_evening',
            'no_neighbour_same_day',
        ))) {
            return false;
        }

        return $this->check_zone_option_enabled($code);
    }

    public function check_parcelshop_enabled()
    {
        return $this->check_zone_option_enabled('parcelshop');
    }

    protected function check_zone_option_enabled($option)
    {
        $cart = WC()->cart;
        if (!$cart) {
            return false;
        }

        $customer = $cart->get_customer();

        if (!$customer) {
            return false;
        }

        if (!$customer->get_shipping_country()) {
            return false;
        }

        $shipping_methods = WC_Shipping::instance()->load_shipping_methods(array(
            'destination' => array(
                'country'  => $customer->get_shipping_country(),
                'state'    => $customer->get_shipping_state(),
                'postcode' => $customer->get_shipping_postcode(),
            ),
        ));

        if (empty($shipping_methods)) {
            return false;
        }

        $continue = false;
        foreach($shipping_methods as $shipping_method) {
            if ($shipping_method->id === 'dhlpwc') {

                $continue = true;
                break;
            }
        }

        if (!$continue) {
            return false;
        }

        /** @var DHLPWC_Model_WooCommerce_Settings_Shipping_Method $shipping_method */
        if ($shipping_method->get_option('enable_option_'.$option) !== 'yes') {
            return false;
        }

        return true;
    }

    public function check_delivery_times_enabled()
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        if (empty($shipping_method)) {
            return false;
        }

        if (!isset($shipping_method['enable_delivery_times'])) {
            return false;
        }

        if ($shipping_method['enable_delivery_times'] != 'yes') {
            return false;
        }

        return true;
    }

    public function check_delivery_times_active()
    {
        if (!$this->check_delivery_times_enabled()) {
            return false;
        }

        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        // Consumers only
        if ($this->check_default_send_to_business()) {
            return false;
        }

        $cart = WC()->cart;
        if (!$cart) {
            return false;
        }

        // Do not show for carts with no shipping required
        if (!$cart->needs_shipping()) {
            return false;
        }

        $customer = $cart->get_customer();

        if (!$customer) {
            return false;
        }

        if (!$customer->get_shipping_country()) {
            return false;
        }

        if (!isset($shipping_method['country'])) {
            return false;
        }

        // Same country only
        if ($customer->get_shipping_country() != $shipping_method['country']) {
            return false;
        }

        // Stock check (if enabled)
        if (isset($shipping_method['enable_delivery_times_stock_check']) && $shipping_method['enable_delivery_times_stock_check'] == 'yes') {
            $cart_content = $cart->get_cart();

            if (!$cart_content) {
                return false;
            }

            $out_of_stock = false;
            foreach ($cart_content as $cart_item_key => $cart_item) {
                if (!$cart_item['data']->is_in_stock()) {
                    $out_of_stock = true;
                    break;

                } else if ($cart_item['data']->is_on_backorder()) {
                    $out_of_stock = true;
                    break;
                } else if ($cart_item['data']->backorders_allowed()) {
                    $stock_info = $cart_item['data']->get_stock_quantity();
                    if ($stock_info < $cart_item['quantity']) {
                        $out_of_stock = true;
                        break;
                    }
                }
            }

            if ($out_of_stock) {
                return false;
            }

        }

        return true;
    }

    public function check_shipping_day($day)
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        $days = array(
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday',
        );

        if (!is_string($day) || !in_array($day, $days)) {
            return false;
        }

        if (empty($shipping_method)) {
            return false;
        }

        if (!isset($shipping_method['enable_shipping_day_'.$day])) {
            return false;
        }

        if ($shipping_method['enable_shipping_day_'.$day] != 'yes') {
            return false;
        }

        return true;
    }

    public function check_alternate_return_address()
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        if (empty($shipping_method)) {
            return false;
        }

        if (!isset($shipping_method['enable_alternate_return_address'])) {
            return false;
        }

        if ($shipping_method['enable_alternate_return_address'] != 'yes') {
            return false;
        }

        return true;
    }

    public function check_default_hide_sender_address()
    {
        $shipping_method = get_option('woocommerce_dhlpwc_settings');

        if (empty($shipping_method)) {
            return false;
        }

        if (!isset($shipping_method['default_hide_sender_address'])) {
            return false;
        }

        if ($shipping_method['default_hide_sender_address'] != 'yes') {
            return false;
        }

        return true;
    }

}

endif;


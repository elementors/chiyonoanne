<?php

if (!defined('ABSPATH')) { exit; }

if (!class_exists('DHLPWC_Model_Service_Order_Meta_Option')) :

class DHLPWC_Model_Service_Order_Meta_Option extends DHLPWC_Model_Core_Singleton_Abstract
{

    const ORDER_OPTION_PREFERENCES = '_dhlpwc_order_option_preferences';

    public function save_option_preference($order_id, $option, $input = null)
    {
        $meta_object = new DHLPWC_Model_Meta_Order_Option_Preference(array(
            'key' => $option,
            'input' => $input,
        ));
        return DHLPWC_Model_Logic_Order_Meta::instance()->add_to_stack(
            self::ORDER_OPTION_PREFERENCES, $order_id, $option, $meta_object
        );
    }

    public function get_option_preference($order_id, $option)
    {
        return DHLPWC_Model_Logic_Order_Meta::instance()->get_from_stack(
            self::ORDER_OPTION_PREFERENCES, $order_id, $option
        );
    }

    public function remove_option_preference($order_id, $option)
    {
        return DHLPWC_Model_Logic_Order_Meta::instance()->remove_from_stack(
            self::ORDER_OPTION_PREFERENCES, $order_id, $option
        );
    }

    public function get_all($order_id)
    {
        return DHLPWC_Model_Logic_Order_Meta::instance()->get_stack(
            self::ORDER_OPTION_PREFERENCES, $order_id
        );
    }

    public function get_keys($order_id)
    {
        $options_data = $this->get_all($order_id);
        $options = array();
        foreach($options_data as $option_data) {
            $option = new DHLPWC_Model_Meta_Order_Option_Preference($option_data);
            $options[] = $option->key;
        }
        return $options;
    }

    public function default_signature($order_id, $options, $to_business)
    {
        $service = DHLPWC_Model_Service_Access_Control::instance();
        $send_signature_checked = $service->check(DHLPWC_Model_Service_Access_Control::ACCESS_DEFAULT_SEND_SIGNATURE);
        if (!$send_signature_checked) {
            return false;
        }

        $allowed_shipping_options = $service->check(DHLPWC_Model_Service_Access_Control::ACCESS_CAPABILITY_ORDER_OPTIONS, array(
            'order_id' => $order_id,
            'options' => $options,
            'to_business' => $to_business,
        ));

        // Disable automatic checking of send signature if there are no parceltypes for it
        if (!array_key_exists(DHLPWC_Model_Meta_Order_Option_Preference::OPTION_HANDT, $allowed_shipping_options)) {
            return false;
        }

        return true;
    }

    public function get_parcelshop($order_id)
    {
        $order = new WC_Order($order_id);

        $service = DHLPWC_Model_Service_Order_Meta_Option::instance();
        $parcelshop_meta = $service->get_option_preference($order->get_order_number(), DHLPWC_Model_Meta_Order_Option_Preference::OPTION_PS);

        if (!$parcelshop_meta) {
            return null;
        }

        $service = DHLPWC_Model_Service_Parcelshop::instance();
        /** @var WC_Order $order */
        $parcelshop = $service->get_parcelshop($parcelshop_meta['input'], $order->get_shipping_country());

        if (!$parcelshop || !isset($parcelshop->name) || !isset($parcelshop->address)) {
            return null;
        }

        return $parcelshop;
    }

    public function add_key_to_stack($key, &$array)
    {
        if (!is_array($array)) {
            if (!$array) {
                $array = array();
            } else {
                return $array;
            }
        }

        if (!in_array($key, $array)) {
            $array[] = $key;
        }
        return $array;
    }

}

endif;

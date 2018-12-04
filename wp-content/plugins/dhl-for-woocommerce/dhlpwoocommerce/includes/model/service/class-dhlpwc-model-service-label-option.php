<?php

if (!defined('ABSPATH')) { exit; }

if (!class_exists('DHLPWC_Model_Service_Label_Option')) :

class DHLPWC_Model_Service_Label_Option extends DHLPWC_Model_Core_Singleton_Abstract
{

    public function get_image_url($option_key = null)
    {
        if (!$option_key) {
            return null;
        }

        return DHLPWC_PLUGIN_URL . 'assets/images/option/' . strtolower($option_key) . '.svg';
    }

    public function get_local_description($option_key)
    {
        return __(sprintf('OPTION_%s', $option_key), 'dhlpwc');
    }

    public function get_request_options($keys = array(), $data = array())
    {
        $request_options = array();
        foreach($keys as $key) {
            $option = new DHLPWC_Model_API_Data_Label_Option(array(
                'key'   => $key,
            ));
            if (array_key_exists($key, $data)) {
                $option->input = sanitize_text_field($data[$key]);
            }
            $request_options[] = $option->to_array();
        }

        return $request_options;
    }

}

endif;

<?php

if (!defined('ABSPATH')) { exit; }

if (!class_exists('DHLPWC_Model_Service_Parcelshop')) :

class DHLPWC_Model_Service_Parcelshop extends DHLPWC_Model_Core_Singleton_Abstract
{

    protected $parcelshop_gateway = 'https://api-gw.dhlparcel.nl/';

    public function get_parcelshops($search, $country, $limit = 13)
    {
        $connector = DHLPWC_Model_API_Connector::instance();

        // TODO do country-based zip coded checks
        if (!$search) {
            return array();
        }

        // TODO whitelist countries
//        $service = DHLPWC_Model_Service_Postcode::instance();
//        $validation = $service->get_validation($country);
        if (!$this->validate_country($country)) {
            return array();
        }

        $parcelshops_data = $connector->get('parcel-shop-locations/'.$country, array(
            'limit' => $limit,
            'fuzzy' => $search,
        ));
        if (!$parcelshops_data || !is_array($parcelshops_data)) {
            return array();
        }

        $parcelshops = array();
        foreach ($parcelshops_data as $parcelshop_data) {
            $parcelshop = new DHLPWC_Model_API_Data_Parcelshop($parcelshop_data);
            $parcelshop->country = $country;
            $parcelshops[] = $parcelshop;
        }
        return $parcelshops;
    }

    public function get_parcelshop($parcelshop_id, $country)
    {
        if (!$parcelshop_id) {
            return null;
        }

        if (!$this->validate_country($country)) {
            return null;
        }

        $connector = DHLPWC_Model_API_Connector::instance();
        $parcelshop_data = $connector->get(sprintf('parcel-shop-locations/'.$country.'/%s', $parcelshop_id), null, 1 * HOUR_IN_SECONDS);
        if (!$parcelshop_data) {
            return null;
        }
        $parcelshop = new DHLPWC_Model_API_Data_Parcelshop($parcelshop_data);
        $parcelshop->country = $country;

        return $parcelshop;
    }

    protected function validate_country($country)
    {
        if (!ctype_upper($country)) {
            return false;
        }

        if (strlen($country) != 2) {
            return false;
        }

        return true;
    }

    public function get_component_translations()
    {
        return array(
            'distance'                  => __('Distance', 'dhlpwc'),
            'search'                    => __('Search', 'dhlpwc'),
            'monday'                    => __('Monday', 'dhlpwc'),
            'tuesday'                   => __('Tuesday', 'dhlpwc'),
            'wednesday'                 => __('Wednesday', 'dhlpwc'),
            'thursday'                  => __('Thursday', 'dhlpwc'),
            'friday'                    => __('Friday', 'dhlpwc'),
            'saturday'                  => __('Saturday', 'dhlpwc'),
            'sunday'                    => __('Sunday', 'dhlpwc'),
            'parcelshop_search'         => __('Search for a ServicePoint...', 'dhlpwc'),
            'closed_period'             => __('Closed from', 'dhlpwc'),
            'closed_period_separator'   => __('till', 'dhlpwc'),
            'closed_period_concatenate' => __('and', 'dhlpwc'),
        );


        //{distance:"Afstand",search:"Zoek",monday:"Maandag",tuesday:"Dinsdag",wednesday:"Woensdag",thursday:"Donderdag",friday:"Vrijdag",saturday:"Zaterdag",sunday:"Zondag",parcelshop_search:"Zoek naar een ServicePoint...",closed_period:"Gesloten van",closed_period_separator:"tot",closed_period_concatenate:"en"}
    }

    public function get_parcelshop_gateway()
    {
        $service = DHLPWC_Model_Service_Access_Control::instance();
        $debug_url = $service->check(DHLPWC_Model_Service_Access_Control::ACCESS_DEBUG);
        return rtrim($debug_url ?: $this->parcelshop_gateway, '/');
    }

}

endif;

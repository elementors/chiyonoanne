<?php

if (!defined('ABSPATH')) { exit; }

if (!class_exists('DHLPWC_Model_WooCommerce_Settings_Shipping_Method')) :

class DHLPWC_Model_WooCommerce_Settings_Shipping_Method extends WC_Shipping_Method
{

    const ENABLE_FREE = 'enable_option_free';
    const ENABLE_TAX_ASSISTANCE = 'enable_tax_assistance';

    const PRICE_FREE = 'price_option_free';
    const FREE_AFTER_COUPON = 'free_after_coupon';

    const PRESET_TRANSLATION_DOMAIN = 'preset_translation_domain';

    /**
     * Constructor for your shipping class
     *
     * @access public
     * @return void
     */
    public function __construct($instance_id = 0)
    {
        parent::__construct($instance_id);

        $this->id = 'dhlpwc';
        $this->method_title = __('DHL for WooCommerce', 'dhlpwc');
        $this->method_description = __('This is the official DHL Plugin for WooCommerce in WordPress. Do you have a WooCommerce webshop and are you looking for an easy way to process shipments within the Netherlands and abroad? This plugin offers you many options. You can easily create shipping labels and offer multiple delivery options in your webshop. Set up your account below.', 'dhlpwc');
        $this->instance_id           = absint( $instance_id );
        $this->title = $this->method_title;
        $this->supports              = array(
            'instance-settings',
            'instance-settings-modal',
            'settings',
        );
        if ($this->get_option('use_shipping_zones') === 'yes') {
            array_unshift($this->supports, 'shipping-zones');
        }
        $this->init();
    }

    /**
     * Init your settings
     *
     * @access public
     * @return void
     */
    public function init()
    {
        // Load the settings API
        $this->init_instance_form_fields();
        $this->init_form_fields();
        $this->init_settings();

        add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
    }

    /**
     * @inheritdoc
     */
    public function get_admin_options_html() {
        return '<div id="dhlpwc_shipping_method_settings">' . parent::get_admin_options_html() . '</div>';
    }

    public function init_instance_form_fields()
    {
        if ($this->get_option('use_shipping_zones') === 'yes') {
            $this->instance_form_fields = $this->get_shipping_method_fields(false);
        } else {
            $this->instance_form_fields = array(
                'plugin_settings'              => array(
                    'title'       => __('Shipping Zones Settings', 'dhlpwc'),
                    'type'        => 'title',
                    'description' => __('Please enable Shipping Zones to use this feature.', 'dhlpwc'),
                ),
            );
        }
    }

    /**
     * Define settings field for this shipping
     * @return void
     */
    public function init_form_fields()
    {
        $country_code = wc_get_base_location();
        switch ($country_code['country']) {
            case 'NL':
                $api_settings_manual_url = 'https://www.dhlparcel.nl/sites/default/files/content/PDF/Handleiding_WooCommerce_koppeling_NL.pdf';
                break;
            default:
                $api_settings_manual_url = 'https://www.dhlparcel.nl/sites/default/files/content/PDF/Handleiding_WooCommerce_koppeling.v.2-EN.pdf';
        }

        $this->form_fields = array_merge(
            array(
                // Enable plugin
                'plugin_settings'              => array(
                    'title'       => __('Plugin Settings', 'dhlpwc'),
                    'type'        => 'title',
                    'description' => __('Enable features of this plugin.', 'dhlpwc'),
                ),
                'enable_all'                   => array(
                    'title'       => __('Enable plugin', 'dhlpwc'),
                    'type'        => 'checkbox',
                    'label'       => __('Enable', 'dhlpwc'),
                    'description' => __("Disabling this turns all of the plugin's features off.", 'dhlpwc'),
                    'default'     => 'yes',
                ),
                'enable_submenu_link'          => array(
                    'title'       => __('Dashboard menu link', 'dhlpwc'),
                    'type'        => 'checkbox',
                    'label'       => __('Enable', 'dhlpwc'),
                    'description' => __("Add a shortcut to the WooCommerce dashboard menu to quickly jump to DHL settings.", 'dhlpwc'),
                    'default'     => 'no',
                ),
                'enable_column_info'           => array(
                    'title'       => __('DHL label info', 'dhlpwc'),
                    'type'        => 'checkbox',
                    'label'       => __('Show', 'dhlpwc'),
                    'description' => __("Add shipping information in an additional column in your order overview.", 'dhlpwc'),
                    'default'     => 'yes',
                ),
                'open_label_links_external'    => array(
                    'title'       => __('Open admin label links in a new window', 'dhlpwc'),
                    'type'        => 'checkbox',
                    'label'       => __('Enable', 'dhlpwc'),
                    'description' => __("Label actions like downloading PDF or opening track & trace will open in a new window.", 'dhlpwc'),
                    'default'     => 'yes',
                ),
                'bulk_container' => array(
                    'type'  => 'dhlpwc_bulk_container',
                ),
            ),

            $this->get_bulk_group_fields('bp_only', __('Choose mailbox, skip if unavailable', 'dhlpwc')),
            $this->get_bulk_group_fields('smallest', __('Choose the smallest available size', 'dhlpwc')),
            $this->get_bulk_group_fields('small_only', sprintf(__("Choose size '%s' only, skip if unavailable", 'dhlpwc'), __('PARCELTYPE_SMALL', 'dhlpwc'))),
            $this->get_bulk_group_fields('medium_only', sprintf(__("Choose size '%s' only, skip if unavailable", 'dhlpwc'), __('PARCELTYPE_MEDIUM', 'dhlpwc'))),
            $this->get_bulk_group_fields('large_only', sprintf(__("Choose size '%s' only, skip if unavailable", 'dhlpwc'), __('PARCELTYPE_LARGE', 'dhlpwc'))),
            $this->get_bulk_group_fields('xsmall_only', sprintf(__("Choose size '%s' only, skip if unavailable", 'dhlpwc'), __('PARCELTYPE_XSMALL', 'dhlpwc'))),
            $this->get_bulk_group_fields('xlarge_only', sprintf(__("Choose size '%s' only, skip if unavailable", 'dhlpwc'), __('PARCELTYPE_XLARGE', 'dhlpwc'))),
            $this->get_bulk_group_fields('largest', __('Choose the largest available size', 'dhlpwc')),

            array(
                'bulk_label_printing' => array(
                    'title'       => __('Bulk label printing', 'dhlpwc'),
                    'type'        => 'checkbox',
                    'label'       => __('Enable', 'dhlpwc'),
                    'default'     => 'no',
                ),
                'enable_track_trace_mail' => array(
                    'title'       => __('Track & trace in mail', 'dhlpwc'),
                    'type'        => 'checkbox',
                    'label'       => __('Enable', 'dhlpwc'),
                    'description' => __("Add track & trace information to the default WooCommerce 'completed order' e-mail if available.", 'dhlpwc'),
                    'default'     => 'no',
                ),
                'enable_track_trace_component' => array(
                    'title'       => __('Track & trace component', 'dhlpwc'),
                    'type'        => 'checkbox',
                    'label'       => __('Show', 'dhlpwc'),
                    'description' => __("Include a track & trace component in the order summary for customers, when they log into the website and check their account information.", 'dhlpwc'),
                    'default'     => 'yes',
                ),
                'google_maps_key' => array(
                    'title'       => __('Google Maps key', 'dhlpwc'),
                    'type'        => 'text',
                    'placeholder' => sprintf(__('Example: %s', 'dhlpwc'), '1a2b3c4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f90a'),
                    'description' => sprintf(
                        __('Please configure your credentials for the Google Maps API. No Google Maps API credentials yet? Get it %shere%s.', 'dhlpwc'),
                        '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">',
                        '</a>'
                    ),
                ),

                // API settings
                'api_settings'                      => array(
                    'title'       => __('Account details', 'dhlpwc'),
                    'type'        => 'title',
                    'description' => sprintf(
                        __('DHL API settings. Still missing API credentials? Follow the instructions %shere%s.', 'dhlpwc'),
                        '<a href="'.esc_url($api_settings_manual_url).'" target="_blank">',
                        '</a>'
                    ),
                ),
                'user_id'    => array(
                    'title'       => __('UserID', 'dhlpwc'),
                    'type'        => 'text',
                    'placeholder' => sprintf(__('Example: %s', 'dhlpwc'), '1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d'),
                ),
                'key'        => array(
                    'title'       => __('Key', 'dhlpwc'),
                    'type'        => 'text',
                    'placeholder' => sprintf(__('Example: %s', 'dhlpwc'), '1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d'),
                ),
                'test_connection' => array(
                    'title'       => __('Test connection', 'dhlpwc'),
                    'type'        => 'button',
                    'disabled'    => true,
                ),
                'account_id' => array(
                    'title'       => __('AccountID', 'dhlpwc'),
                    'type'        => 'text',
                    'placeholder' => sprintf(__('Example: %s', 'dhlpwc'), '01234567'),
                ),
                'organization_id' => array(
                    'title'       => __('OrganizationID', 'dhlpwc'),
                    'type'        => 'text',
                    'placeholder' => sprintf(__('Example: %s', 'dhlpwc'), '1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d'),
                ),

                // Shipment options
                'shipment_options_settings' => array(
                    'title'       => __('Shipment options', 'dhlpwc'),
                    'type'        => 'title',
                    'description' => __("Choose the shipment options for the recipients of your webshop.", 'dhlpwc'),
                ),

                'default_send_to_business' => array(
                    'title'       => __('Send to business by default', 'dhlpwc'),
                    'type'        => 'checkbox',
                    'label'       => __('Enable', 'dhlpwc'),
                    'description' => __("When enabled, by default labels will be created for business shipments and the checkout will show business shipping options.", 'dhlpwc'),
                    'default'     => 'no',
                ),

                'check_default_send_signature' => array(
                    'title'       => __('Always enable required signature if available', 'dhlpwc'),
                    'type'        => 'checkbox',
                    'label'       => __('Enable', 'dhlpwc'),
                    'description' => __("When creating a label, always select the signature option by default if the service is available.", 'dhlpwc'),
                    'default'     => 'no',
                ),

                self::PRESET_TRANSLATION_DOMAIN => array(
                    'title'       => __('Replace text label translation domain', 'dhlpwc'),
                    'type'        => 'text',
                    'description' => __("If using replacement text labels for shipping methods, it's possible to filter it with a translation domain. To use the text as-is, leave this field empty.", 'dhlpwc'),
                ),

                'use_shipping_zones' => array(
                    'title'       => __('Use shipping zones', 'dhlpwc'),
                    'type'        => 'checkbox',
                    'label'       => __('Enable', 'dhlpwc'),
                    'description' => __("Set shipping methods per shipping zone.", 'dhlpwc'),
                    'default'     => 'no',
                ),
            ),

            $this->get_shipping_method_fields(),

            array(
                // Delivery times
                'delivery_times_settings' => array(
                    'title'       => __('Delivery times', 'dhlpwc'),
                    'type'        => 'title',
                    'description' => __('Allow customers to select delivery times and manage when to send packages', 'dhlpwc'),
                ),
            ),

            $this->get_delivery_times_method_fields(),

            array(
                // Default shipping address
                'default_shipping_address_settings' => array(
                    'title'       => __('Default Shipping Address', 'dhlpwc'),
                    'type'        => 'title',
                    'description' => __('Fill in the details of your shipping address.', 'dhlpwc'),
                ),
            ),

            $this->get_address_fields(),

            array(
                'enable_alternate_return_address'                   => array(
                    'title'       => __('Different return address', 'dhlpwc'),
                    'type'        => 'checkbox',
                    'label'       => __('Enable', 'dhlpwc'),
                    'description' => __("Use a different address for return labels.", 'dhlpwc'),
                    'default'     => 'no',
                ),
            ),

            $this->get_address_fields('return_address_'),

            array(
                'default_hide_sender_address'                   => array(
                    'title'       => __('Default hide sender address', 'dhlpwc'),
                    'type'        => 'checkbox',
                    'label'       => __('Enable', 'dhlpwc'),
                    'description' => __("Set a default address for the 'Hide sender' service option.", 'dhlpwc'),
                    'default'     => 'no',
                ),
            ),

            $this->get_address_fields('hide_sender_address_'),

            $this->get_shipping_method_fields(),

            array(
                // Debug
                'developer_settings'                => array(
                    'title'       => __('Debug Settings', 'dhlpwc'),
                    'type'        => 'title',
                    'description' => __('Settings for developers.', 'dhlpwc'),
                ),
                'enable_debug'                      => array(
                    'title'       => __('Report errors', 'dhlpwc'),
                    'type'        => 'checkbox',
                    'label'       => __('Enable', 'dhlpwc'),
                    'description' => __('Enable this and select one of the reporting methods below to automatically send errors of this plugin to the development team.', 'dhlpwc'),
                ),
                'enable_debug_mail'                 => array(
                    'title'       => __('By mail', 'dhlpwc'),
                    'type'        => 'checkbox',
                    'label'       => __('Enable', 'dhlpwc'),
                    'description' => __('Errors will be automatically forwarded by e-mail.', 'dhlpwc'),
                ),
                'debug_url'                         => array(
                    'title'       => __('By custom URL', 'dhlpwc'),
                    'type'        => 'text',
                    'description' => __("Monitoring URL. Used by developers. Can be used for active monitoring, please contact support for this feature. Will not be used if left empty.", 'dhlpwc'),
                ),
                'debug_external_url' => array(
                    'title'       => __('External custom URL', 'dhlpwc'),
                    'type'        => 'text',
                    'description' => __("Alternative external URL. Used by developers. Will not be used if left empty.", 'dhlpwc'),
                ),
            )
        );
    }

    protected function get_option_group_fields($code, $title, $class = '')
    {
        $option_settings = array(
            'enable_option_' . $code => array(
                'title'             => __($title, 'dhlpwc'),
                'type'              => 'checkbox',
                'class'             => "dhlpwc-grouped-option dhlpwc-option-grid['" . $code . "'] " . $class,
                'default'           => 'no',
                'custom_attributes' => array(
                    'data-option-group' => $code,
                ),
            ),

            'price_option_' . $code => array(
                'type'              => 'price',
                'class'             => "dhlpwc-grouped-option dhlpwc-price-input dhlpwc-option-grid['" . $code . "'] " . $class,
                'default'           => '0.00',
                'custom_attributes' => array(
                    'data-dhlpwc-currency-symbol' => get_woocommerce_currency_symbol(),
                    'data-dhlpwc-currency-pos'    => get_option('woocommerce_currency_pos'),
                    'data-option-group'           => $code,
                ),
            ),

            'enable_free_option_' . $code => array(
                'type'              => 'checkbox',
                'class'             => "dhlpwc-grouped-option dhlpwc-option-grid['" . $code . "'] " . $class,
                'default'           => 'no',
                'custom_attributes' => array(
                    'data-option-group' => $code,
                ),
            ),

            'free_price_option_' . $code => array(
                'type'              => 'price',
                'class'             => "dhlpwc-grouped-option dhlpwc-price-input dhlpwc-option-grid['" . $code . "'] " . $class,
                'default'           => '0.00',
                'custom_attributes' => array(
                    'data-dhlpwc-currency-symbol' => get_woocommerce_currency_symbol(),
                    'data-dhlpwc-currency-pos'    => get_option('woocommerce_currency_pos'),
                    'data-option-group'           => $code,
                ),
            ),

            'alternative_option_text_' . $code => array(
                'type'              => 'text',
                'class'             => "dhlpwc-grouped-option dhlpwc-option-grid['" . $code . "'] " . $class,
                'default'           => '',
                'placeholder'       => __('Use default text label', 'dhlpwc'),
                'custom_attributes' => array(
                    'data-option-group' => $code,
                ),
            ),
        );

        return $option_settings;
    }

    protected function get_shipping_method_fields($is_global = true)
    {
        if ($is_global) {
            $class = 'dhlpwc-global-shipping-setting';
        } else {
            $class = 'dhlpwc-instance-shipping-setting';
        }

        $option_settings = array(
            self::ENABLE_TAX_ASSISTANCE => array(
                'title'       => __('Enter prices with tax included', 'dhlpwc'),
                'type'        => 'checkbox',
                'description' => __("Turn this on to enter prices with the tax included. Turn this off to enter prices without tax.", 'dhlpwc'),
                'class'       => $class,
                'default'     => 'yes',
            ),

            self::ENABLE_FREE => array(
                'title'       => __('Free or discounted shipping', 'dhlpwc'),
                'type'        => 'checkbox',
                'label'       => __('Enable', 'dhlpwc'),
                'description' => __("Offer free shipping (over a certain amount).", 'dhlpwc'),
                'class'       => $class,
                'default'     => 'no',
            ),

            self::PRICE_FREE       => array(
                'title'             => __('Free or discounted shipping threshold', 'dhlpwc'),
                'type'              => 'price',
                'description'       => __("Free or discounted shipping prices are applied when the total price is over the inputted value.", 'dhlpwc'),
                'default'           => '0.00',
                'class'             => 'dhlpwc-price-input '.$class,
                'custom_attributes' => array(
                    'data-dhlpwc-currency-symbol'     => get_woocommerce_currency_symbol(),
                    'data-dhlpwc-currency-pos' => get_option('woocommerce_currency_pos'),
                ),
            ),

            self::FREE_AFTER_COUPON => array(
                'title'       => __('Free or discounted shipping and coupons', 'dhlpwc'),
                'type'        => 'checkbox',
                'label'       => __('Calculate after applying coupons', 'dhlpwc'),
                'description' => __("Calculate eligibility for free or discounted shipping after applying coupons.", 'dhlpwc'),
                'class'       => $class,
                'default'     => 'no',
            ),

        );

        $service = DHLPWC_Model_Service_Shipping_Preset::instance();
        $presets = $service->get_presets();

        $option_settings['grouped_option_container'] = array(
            'type'  => 'dhlpwc_grouped_option_container',
        );

        foreach($presets as $data) {
            $preset = new DHLPWC_Model_Meta_Shipping_Preset($data);
            $option_settings = array_merge($option_settings, $this->get_option_group_fields($preset->setting_id, $preset->title, $class));
        }

        return $option_settings;
    }

    protected function get_delivery_times_method_fields()
    {
        $service = DHLPWC_Model_Service_Shipping_Preset::instance();
        $same_day = $service->find_preset('same_day');
        $home = $service->find_preset('home');
        $no_neighbour_same_day = $service->find_preset('no_neighbour_same_day');
        $no_neighbour = $service->find_preset('no_neighbour');

        return array_merge(
            array(
                'enable_delivery_times' => array(
                    'title'       => __('Enable delivery times', 'dhlpwc'),
                    'type'        => 'checkbox',
                    'label'       => __('Enable', 'dhlpwc'),
                    'description' => __('Show delivery date and time selection in the checkout and show delivery dates in the dashboard.', 'dhlpwc'),
                ),
                'enable_delivery_times_stock_check' => array(
                    'title'       => __('Check stock', 'dhlpwc'),
                    'type'        => 'checkbox',
                    'label'       => __('Enable', 'dhlpwc'),
                    'default'     => 'yes',
                    'description' => __('Only show delivery times when all cart items are in stock.', 'dhlpwc'),
                ),
                'delivery_times_container' => array(
                    'type'  => 'dhlpwc_delivery_times_container',
                ),
            ),

            $this->get_delivery_times_group_fields($same_day->setting_id, sprintf(__('%s available until', 'dhlpwc'), $same_day->title), true),
            $this->get_delivery_times_group_fields($no_neighbour_same_day->setting_id, sprintf(__('%s available until', 'dhlpwc'), $no_neighbour_same_day->title), true),
            $this->get_delivery_times_group_fields($home->setting_id, sprintf(__('%s available until', 'dhlpwc'), $home->title)),
            $this->get_delivery_times_group_fields($no_neighbour->setting_id, sprintf(__('%s available until', 'dhlpwc'), $no_neighbour->title)),

            $this->get_shipping_days()
        );
    }

    protected function get_shipping_days()
    {
        $days = array(
            'monday'    => __('Monday', 'dhlpwc'),
            'tuesday'   => __('Tuesday', 'dhlpwc'),
            'wednesday' => __('Wednesday', 'dhlpwc'),
            'thursday'  => __('Thursday', 'dhlpwc'),
            'friday'    => __('Friday', 'dhlpwc'),
            'saturday'  => __('Saturday', 'dhlpwc'),
            'sunday'    => __('Sunday', 'dhlpwc'),
        );

        $defaults = array(
            'monday'    => 'yes',
            'tuesday'   => 'yes',
            'wednesday' => 'yes',
            'thursday'  => 'yes',
            'friday'    => 'yes',
            'saturday'  => 'no',
            'sunday'    => 'no',
        );

        $shipping_days = array();
        foreach($days as $day => $day_text) {
            $shipping_days['enable_shipping_day_'.$day] = array(
                'title'       => sprintf(__('Ship on %ss', 'dhlpwc'), $day_text),
                'type'        => 'checkbox',
                'label'       => __('Enable', 'dhlpwc'),
                'default'     => $defaults[$day],
            );
        }

        return $shipping_days;
    }

    protected function get_days_for_sending()
    {
        $days = range(1, 14);

        $list = array();
        foreach ($days as $day) {
            if ($day === 1) {
                $list[$day] = __('Next day', 'dhlpwc');
            } else {
                $list[$day] = sprintf(__('%s day', 'dhlpwc'), $day);
            }
        }

        return $list;
    }

    protected function get_time_for_sending($ceil = 24)
    {
        $hours = range(1, $ceil);

        $list = array();
        foreach ($hours as $hour) {
            if ($hour === 24) {
                $list[$hour] = '23:59';
            } else {
                $list[$hour] = sprintf('%s:00', $hour);
            }
        }

        return $list;
    }

    protected function get_delivery_times_group_fields($code, $title, $skip_day_select = false)
    {
        $time_ceiling = $skip_day_select ? 18 : 24;

        $options = array(
            'enable_delivery_time_' . $code  => array(
                'type'              => 'checkbox',
                'class'             => "dhlpwc-delivery-times-option dhlpwc-delivery-times-grid['" . $code . "']",
                'default'           => 'no',
                'custom_attributes' => array(
                    'data-delivery-times-group' => $code,
                ),
            ),
            'delivery_day_cut_off_' . $code  => array(
                'type'              => 'select',
                'class'             => "dhlpwc-delivery-times-option dhlpwc-delivery-times-grid['" . $code . "']",
                'options'           => $this->get_days_for_sending(),
                'custom_attributes' => array(
                    'data-delivery-times-group' => $code,
                ),
            ),
            'delivery_time_cut_off_' . $code => array(
                'title'             => $title,
                'type'              => 'select',
                'class'             => "dhlpwc-delivery-times-option dhlpwc-delivery-times-grid['" . $code . "']",
                'options'           => $this->get_time_for_sending($time_ceiling),
                'default'           => 16,
                'custom_attributes' => array(
                    'data-delivery-times-group' => $code,
                ),
            ),
        );

        if ($skip_day_select) {
            $options['delivery_day_cut_off_'.$code] = null;
            unset($options['delivery_day_cut_off_'.$code]);
        }

        return $options;
    }

    protected function get_bulk_group_fields($code, $title)
    {
        return array(
            'enable_bulk_option_' . $code  => array(
                'title'             => __($title, 'dhlpwc'),
                'type'              => 'checkbox',
                'class'             => "dhlpwc-bulk-option dhlpwc-bulk-grid['" . $code . "']",
                'default'           => 'no',
                'custom_attributes' => array(
                    'data-bulk-group' => $code,
                ),
            )
        );
    }

    public function get_address_fields($prefix = null)
    {
        switch($prefix) {
            case 'return_address_':
                $class = 'dhlpwc-return-address-setting';
                break;
            case 'hide_sender_address_':
                $class = 'dhlpwc-hide-sender-address-setting';
                break;
            default:
                $class = 'dhlpwc-default-address-setting';
        }

        $settings = array(
            $prefix.'first_name'                        => array(
                'title' => __('First Name', 'dhlpwc'),
                'type'  => 'text',
                'class' => $class,
            ),
            $prefix.'last_name'                         => array(
                'title' => __('Last Name', 'dhlpwc'),
                'type'  => 'text',
                'class' => $class,
            ),
            $prefix.'company'                           => array(
                'title' => __('Company', 'dhlpwc'),
                'type'  => 'text',
                'class' => $class,
            ),

            $prefix.'postcode'                          => array(
                'title' => __('Postcode', 'dhlpwc'),
                'type'  => 'text',
                'class' => $class,
            ),
            $prefix.'city'                              => array(
                'title' => __('City', 'dhlpwc'),
                'type'  => 'text',
                'class' => $class,
            ),
            $prefix.'street'                            => array(
                'title' => __('Street', 'dhlpwc'),
                'type'  => 'text',
                'class' => $class,
            ),
            $prefix.'number'                            => array(
                'title' => __('Number', 'dhlpwc'),
                'type'  => 'text',
                'class' => $class,
            ),
            $prefix.'country' => array(
                'title' => __('Country', 'dhlpwc'),
                'type' => 'select',
                'options' => array(
                    'NL' => __('Netherlands', 'dhlpwc'),
                    'BE' => __('Belgium', 'dhlpwc'),
                    'LU' => __('Luxembourg', 'dhlpwc'),
                    'CH' => __('Switzerland', 'dhlpwc'),
                ),
                'default' => 'NL',
                'class' => $class,
            ),
            $prefix.'email'                             => array(
                'title' => __('Email', 'dhlpwc'),
                'type'  => 'text',
                'class' => $class,
            ),
            $prefix.'phone'                             => array(
                'title' => __('Phone', 'dhlpwc'),
                'type'  => 'text',
                'class' => $class,
            ),
        );

        // Remove country settings for SSN
        if ($prefix == 'hide_sender_address_') {
            $settings[$prefix.'country'] = null;
            unset($settings[$prefix.'country']);
        }

        return $settings;
    }



    public function calculate_shipping($package = array())
    {
        $domain = $this->get_option(self::PRESET_TRANSLATION_DOMAIN);

        $service = DHLPWC_Model_Service_Shipping_Preset::instance();
        $presets = $service->get_presets();

        $access_service = DHLPWC_Model_Service_Access_Control::instance();
        $allowed_shipping_options = $access_service->check(DHLPWC_Model_Service_Access_Control::ACCESS_CAPABILITY_OPTIONS);

        // When using delivery times and it is not showing (out of stock, unsupported country, or unavailable), don't allow same day delivery to show
        if ($access_service->check(DHLPWC_Model_Service_Access_Control::ACCESS_DELIVERY_TIMES)) {
            $delivery_times_active = $access_service->check(DHLPWC_Model_Service_Access_Control::ACCESS_DELIVERY_TIMES_ACTIVE);
            if (!$delivery_times_active) {
                if (array_key_exists(DHLPWC_Model_Meta_Order_Option_Preference::OPTION_SDD, $allowed_shipping_options)) {
                    $allowed_shipping_options[DHLPWC_Model_Meta_Order_Option_Preference::OPTION_SDD] = null;
                    unset($allowed_shipping_options[DHLPWC_Model_Meta_Order_Option_Preference::OPTION_SDD]);
                }
            }
        }

        foreach($presets as $data) {
            $preset = new DHLPWC_Model_Meta_Shipping_Preset($data);

            $check_allowed_options = true;
            foreach($preset->options as $preset_option) {
                if (!array_key_exists($preset_option, $allowed_shipping_options)) {
                    $check_allowed_options = false;
                }
            }

            if ($this->get_option('enable_option_'.$preset->setting_id) === 'yes' && $check_allowed_options === true) {

                $alternate_text = $this->get_option('alternative_option_text_'.$preset->setting_id);
                if (!empty($alternate_text)) {
                    if (!empty($domain)) {
                        $title = __($alternate_text, $domain);
                    } else {
                        $title = $alternate_text;
                    }
                } else {
                    $title = __($preset->title, 'dhlpwc');
                }

                $this->add_rate(array(
                    'id'    => 'dhlpwc-'.$preset->frontend_id,
                    'label' => $title,
                    'cost'  => $this->calculate_cost($package, $preset->setting_id),
                ));

            }
        }

        $this->update_taxes();
    }

    protected function generate_dhlpwc_bulk_container_html($key, $data)
    {
        $view = new DHLPWC_Template('admin.settings.bulk-header');
        return $view->render(array(), false);
    }

    protected function generate_dhlpwc_delivery_times_container_html($key, $data)
    {
        $view = new DHLPWC_Template('admin.settings.delivery-times-header');
        return $view->render(array(), false);
    }

    protected function generate_dhlpwc_grouped_option_container_html($key, $data)
    {
        $view = new DHLPWC_Template('admin.settings.options-grid-header');
        return $view->render(array(), false);
    }

    protected function calculate_cost($package = array(), $option)
    {
        if ($this->get_option(self::ENABLE_FREE) === 'yes') {
            if ($this->get_subtotal_price($package) >= $this->get_option(self::PRICE_FREE)) {
                return $this->get_free_price($option);
            }
        }
        return $this->get_option('price_option_'.$option);
    }

    protected function get_free_price($option)
    {
        if ($this->get_option('enable_free_option_'.$option) === 'yes') {
            return round($this->get_option('free_price_option_'.$option), wc_get_price_decimals());
        }
        return $this->get_option('price_option_'.$option);
    }

    protected function get_subtotal_price($package = array())
    {
        if ($this->get_option(self::FREE_AFTER_COUPON) === 'yes') {
            $subtotal = 0;
            foreach($package['contents'] as $key => $order)
            {
                $subtotal += $order['line_total'] + $order['line_tax'];
            }
            return round($subtotal, wc_get_price_decimals());
        }

        return $package['cart_subtotal'];
    }

    protected function update_taxes()
    {
        if ($this->get_option(self::ENABLE_TAX_ASSISTANCE) === 'yes') {
            foreach($this->rates as $rate_id => $rate) {
                /** @var WC_Shipping_Rate $rate */
                $rate->set_cost($rate->get_cost() - $rate->get_shipping_tax());
            }
        }
    }
}

endif;

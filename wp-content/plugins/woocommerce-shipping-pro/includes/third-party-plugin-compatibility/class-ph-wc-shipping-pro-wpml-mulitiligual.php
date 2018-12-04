<?php

if( class_exists( "Ph_WC_Shipping_Pro_WPML_Mulitiligual", false) ) {
    new Ph_WC_Shipping_Pro_WPML_Mulitiligual();
    return;
}

if( ! class_exists("Ph_WC_Shipping_Pro_WPML_Mulitiligual") ) {
    /**
     * To Support WPML Multilingual site.
     */
    class Ph_WC_Shipping_Pro_WPML_Mulitiligual {

        /**
         * WPML Current Language.
         */
        private static $wpml_current_language;

        /**
         * WPML Default Language.
         */
        private static $wpml_default_language;

        /**
         * Constructor.
         */
        public function __construct() {
            // To switch language to default language.
            add_action( 'ph_woocommerce_shipping_pro_before_shipping_calculation', array( $this, 'switch_lang_to_default_lang') );
            // To switch language to current language.
            add_action( 'ph_woocommerce_shipping_pro_after_shipping_calculation', array( $this, 'switch_lang_to_current_lang') );
        }

        /**
         * Switch Laguage to WPML default language.
         */
        public function switch_lang_to_default_lang() {

            // Access wpml default language
            self::$wpml_default_language 	= apply_filters( 'wpml_default_language', null );
            // Access wpml current language
            self::$wpml_current_language 	= apply_filters( 'wpml_current_language', null );
            // Switch language to default language so that we can get the data in default language
            do_action( 'wpml_switch_language', self::$wpml_default_language );
        }

        /**
         * Switch language back to the current language.
         */
        public function switch_lang_to_current_lang() {
		    do_action( 'wpml_switch_language', self::$wpml_current_language );
        }
    }
    new Ph_WC_Shipping_Pro_WPML_Mulitiligual();
}
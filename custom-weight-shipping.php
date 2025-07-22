<?php
/**
 * Plugin Name: Custom Weight Based Shipping
 * Description: WooCommerce shipping method based on product weight.
 * Version: 1.0
 * Author: absoftlab
 * Author URI: https://absoftlab.com
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function cws_init() {
    if ( ! class_exists( 'WC_Shipping_Method' ) ) return;

    class WC_Weight_Based_Shipping extends WC_Shipping_Method {

        public function __construct() {
            $this->id                 = 'weight_based_shipping';
            $this->method_title       = 'Weight Based Shipping';
            $this->method_description = 'Shipping cost calculated based on total cart weight.';
            $this->enabled            = "yes";
            $this->title              = "Weight Based Shipping";

            $this->init();
        }

        function init() {
            $this->init_form_fields();
            $this->init_settings();
            $this->enabled = $this->get_option( 'enabled' );
            $this->title   = $this->get_option( 'title' );

            add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
        }

        public function calculate_shipping( $package = array() ) {
            $weight = 0;
            foreach ( $package['contents'] as $item ) {
                if ( $item['data']->has_weight() ) {
                    $weight += floatval( $item['data']->get_weight() ) * intval( $item['quantity'] );
                }
            }

            $cost = 0;

            // Weight-based rate logic (example)
            if ( $weight <= 1 ) {
                $cost = 50;
            } elseif ( $weight <= 5 ) {
                $cost = 100;
            } elseif ( $weight <= 10 ) {
                $cost = 150;
            } else {
                $cost = 200;
            }

            $rate = array(
                'id'    => $this->id,
                'label' => $this->title,
                'cost'  => $cost,
                'calc_tax' => 'per_order'
            );

            $this->add_rate( $rate );
        }
    }
}

add_action( 'woocommerce_shipping_init', 'cws_init' );

function add_cws_method( $methods ) {
    $methods['weight_based_shipping'] = 'WC_Weight_Based_Shipping';
    return $methods;
}

add_filter( 'woocommerce_shipping_methods', 'add_cws_method' );

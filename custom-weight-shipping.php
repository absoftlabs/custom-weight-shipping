<?php
/**
 * Plugin Name: Weight Based Shipping - Dhaka & Outside
 * Description: Auto-selects editable weight-based shipping based on district (billing_state). Supports Bangla and English district names.
 * Version: 2.1.4
 * Author: absoftlab
 * Author URI: https://absoftlab.com
 */


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Load custom shipping methods
 */
function absoftlab_register_weight_shipping_methods() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-shipping-inside-dhaka.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-shipping-outside-dhaka.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-shipping-sundarban-courier.php';


    add_filter( 'woocommerce_shipping_methods', function( $methods ) {
        $methods['inside_dhaka'] = 'WC_Shipping_Inside_Dhaka';
        $methods['outside_dhaka'] = 'WC_Shipping_Outside_Dhaka';
        $methods['sundarban_courier'] = 'WC_Shipping_Sundarban_Courier';
        return $methods;
    });
}
add_action( 'woocommerce_shipping_init', 'absoftlab_register_weight_shipping_methods' );

/**
 * Enable only one shipping method based on billing state
 */
add_filter( 'woocommerce_shipping_method_is_available', 'absoftlab_limit_shipping_by_district', 20, 2 );
function absoftlab_limit_shipping_by_district( $is_available, $method ) {
    if ( ! in_array( $method->id, ['inside_dhaka', 'outside_dhaka'] ) ) {
        return $is_available;
    }

    $packages = WC()->shipping()->get_packages();
    if ( empty( $packages[0]['destination']['state'] ) ) {
        return $is_available; // fallback if state not selected yet
    }

    $district = strtolower( trim( $packages[0]['destination']['state'] ) );
    $district = str_replace(['city', 'district', 'জেলা', 'শহর'], '', $district);
    $district = trim($district);

    $inside_dhaka = ['dhaka', 'ঢাকা'];

    if ( in_array( $district, $inside_dhaka ) ) {
        return $method->id === 'inside_dhaka';
    } else {
        return $method->id === 'outside_dhaka';
    }
}

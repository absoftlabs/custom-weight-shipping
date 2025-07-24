<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WC_Shipping_Sundarban_Courier extends WC_Shipping_Method {

    public function __construct() {
        $this->id = 'sundarban_courier';
        $this->method_title = __( 'Sundarban Courier', 'absoftlab' );
        $this->method_description = __( 'Shipping via Sundarban Courier based on weight.', 'absoftlab' );

        $this->enabled = "yes";
        $this->title = __( 'Sundarban Courier', 'absoftlab' );

        $this->init();
    }

    public function init() {
        $this->init_form_fields();
        $this->init_settings();
        $this->title = $this->get_option( 'title', $this->title );

        add_action( 'woocommerce_update_options_shipping_' . $this->id, [ $this, 'process_admin_options' ] );
    }

    public function init_form_fields() {
        $this->form_fields = [
            'title' => [
                'title' => __( 'Method Title', 'absoftlab' ),
                'type'  => 'text',
                'default' => __( 'Sundarban Courier', 'absoftlab' )
            ],

            'rate_5'  => ['title' => '0 - 5 kg', 'type' => 'number', 'default' => 150],
            'rate_10' => ['title' => '5 - 10 kg', 'type' => 'number', 'default' => 180],
            'rate_15' => ['title' => '10 - 15 kg', 'type' => 'number', 'default' => 200],
            'rate_20' => ['title' => '15 - 20 kg', 'type' => 'number', 'default' => 250],
            'rate_25' => ['title' => '20 - 25 kg', 'type' => 'number', 'default' => 300],
            'rate_30' => ['title' => '25 - 30 kg', 'type' => 'number', 'default' => 400],
            'rate_40' => ['title' => '30 - 40 kg', 'type' => 'number', 'default' => 500],
        ];
    }

    public function calculate_shipping( $package = array() ) {
        $weight = 0;

        foreach ( $package['contents'] as $item ) {
            $weight += floatval( $item['data']->get_weight() ) * $item['quantity'];
        }

        $cost = $this->get_shipping_cost( $weight );

        $rate = array(
            'id'    => $this->id,
            'label' => $this->title,
            'cost'  => $cost,
            'calc_tax' => 'per_item'
        );

        $this->add_rate( $rate );
    }

    private function get_shipping_cost( $weight ) {
        $brackets = [
            5  => 'rate_5',
            10 => 'rate_10',
            15 => 'rate_15',
            20 => 'rate_20',
            25 => 'rate_25',
            30 => 'rate_30',
            40 => 'rate_40',
        ];

        foreach ( $brackets as $limit => $key ) {
            if ( $weight <= $limit ) {
                return $this->get_option( $key, 0 );
            }
        }

        return 0;
    }
}

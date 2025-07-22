<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


class WC_Shipping_Inside_Dhaka extends WC_Shipping_Method {

    public function __construct() {
        $this->id = 'inside_dhaka';
        $this->method_title = __( 'Inside Dhaka', 'absoftlab' );
        $this->method_description = __( 'Shipping rates for Inside Dhaka based on weight.', 'absoftlab' );

        $this->enabled = "yes";
        $this->title = __( 'Inside Dhaka', 'absoftlab' );

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
                'type' => 'text',
                'default' => __( 'Inside Dhaka', 'absoftlab' )
            ],

            'rate_0_5' => ['title' => '0 - 0.5 kg', 'type' => 'number', 'default' => 80],
            'rate_1' => ['title' => '0.5 - 1 kg', 'type' => 'number', 'default' => 100],
            'rate_2' => ['title' => '1 - 2 kg', 'type' => 'number', 'default' => 120],
            'rate_3' => ['title' => '2 - 3 kg', 'type' => 'number', 'default' => 130],
            'rate_4' => ['title' => '3 - 4 kg', 'type' => 'number', 'default' => 140],
            'rate_5' => ['title' => '4 - 5 kg', 'type' => 'number', 'default' => 150],
            'rate_6' => ['title' => '5 - 6 kg', 'type' => 'number', 'default' => 170],
            'rate_7' => ['title' => '6 - 7 kg', 'type' => 'number', 'default' => 190],
            'rate_8' => ['title' => '7 - 8 kg', 'type' => 'number', 'default' => 210],
            'rate_9' => ['title' => '8 - 9 kg', 'type' => 'number', 'default' => 230],
            'rate_10' => ['title' => '9 - 10 kg', 'type' => 'number', 'default' => 250],
            'rate_12' => ['title' => '10 - 12 kg', 'type' => 'number', 'default' => 280],
            'rate_15' => ['title' => '12 - 15 kg', 'type' => 'number', 'default' => 320],
            'rate_17' => ['title' => '15 - 17 kg', 'type' => 'number', 'default' => 350],
            'rate_20' => ['title' => '17 - 20 kg', 'type' => 'number', 'default' => 400],
            'rate_25' => ['title' => '20 - 25 kg', 'type' => 'number', 'default' => 500],
            'rate_30' => ['title' => '25 - 30 kg', 'type' => 'number', 'default' => 600],
            'rate_35' => ['title' => '30 - 35 kg', 'type' => 'number', 'default' => 700],
            'rate_40' => ['title' => '35 - 40 kg', 'type' => 'number', 'default' => 800],
            'rate_45' => ['title' => '40 - 45 kg', 'type' => 'number', 'default' => 900],
            'rate_50' => ['title' => '45 - 50 kg', 'type' => 'number', 'default' => 1000],
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
            0.5 => 'rate_0_5', 1 => 'rate_1', 2 => 'rate_2', 3 => 'rate_3',
            4 => 'rate_4', 5 => 'rate_5', 6 => 'rate_6', 7 => 'rate_7',
            8 => 'rate_8', 9 => 'rate_9', 10 => 'rate_10', 12 => 'rate_12',
            15 => 'rate_15', 17 => 'rate_17', 20 => 'rate_20', 25 => 'rate_25',
            30 => 'rate_30', 35 => 'rate_35', 40 => 'rate_40', 45 => 'rate_45', 50 => 'rate_50'
        ];

        foreach ( $brackets as $limit => $key ) {
            if ( $weight <= $limit ) {
                return $this->get_option( $key, 0 );
            }
        }

        return 0;
    }
}

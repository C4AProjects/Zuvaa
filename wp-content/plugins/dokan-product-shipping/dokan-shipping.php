<?php
/*
Plugin Name: Dokan - Per product shipping
Plugin URI: http://wedevs.com/
Description: Per product shipping for sellers in Dokan
Version: 0.4
Author: Tareq Hasan
Author URI: http://tareq.wedevs.com/
License: GPL2
*/

/**
 * Copyright (c) 2014 Tareq Hasan (email: tareq@wedevs.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( is_admin() ) {
    require_once dirname( __FILE__ ) . '/lib/wedevs-updater.php';

    new WeDevs_Plugin_Update_Checker( plugin_basename( __FILE__ ) );
}

/**
 * Dokan_Per_Product_Shipping class
 *
 * @class Dokan_Per_Product_Shipping The class that holds the entire Dokan_Per_Product_Shipping plugin
 */
class Dokan_Per_Product_Shipping {

    /**
     * Constructor for the Dokan_Per_Product_Shipping class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses is_admin()
     * @uses add_action()
     */
    public function __construct() {

        // Localize our plugin
        add_action( 'init', array( $this, 'localization_setup' ) );

        add_action( 'woocommerce_shipping_init', array($this, 'include_shipping' ) );
        add_action( 'woocommerce_shipping_methods', array($this, 'register_shipping' ) );

        add_action( 'dokan_process_product_meta', array($this, 'update_meta' ) );
        add_action( 'dokan_product_options_shipping', array($this, 'frontend_option' ) );
        add_action( 'woocommerce_product_tabs', array($this, 'register_product_tab' ) );

        add_action( 'woocommerce_after_checkout_validation', array($this, 'validate_country' ) );
    }

    /**
     * Initializes the Dokan_Per_Product_Shipping() class
     *
     * Checks for an existing Dokan_Per_Product_Shipping() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new Dokan_Per_Product_Shipping();
        }

        return $instance;
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'dokan-shipping', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Include main shipping integration
     *
     * @return void
     */
    function include_shipping() {
        require_once dirname( __FILE__ ) . '/includes/shipping.php';
    }

    /**
     * Register shipping method
     *
     * @param array $methods
     * @return array
     */
    function register_shipping( $methods ) {
        $methods[] = 'Dokan_WC_Per_Product_Shipping';

        return $methods;
    }

    /**
     * Save product meta
     *
     * @param int $post_id
     */
    function update_meta( $post_id ) {

        $enable = isset( $_POST['_dps_ship_enable'] ) ? 'yes' : 'no';
        update_post_meta( $post_id, '_dps_ship_enable', $enable );

        if ( isset( $_POST['_dps_pt'] ) ) {
            update_post_meta( $post_id, '_dps_pt', $_POST['_dps_pt'] );
        }

        if ( isset( $_POST['_dps_from'] ) ) {
            update_post_meta( $post_id, '_dps_from', $_POST['_dps_from'] );
        }

        if ( isset( $_POST['_dps_ship_policy'] ) ) {
            update_post_meta( $post_id, '_dps_ship_policy', wp_kses_post( $_POST['_dps_ship_policy'] ) );
        }

        if ( isset( $_POST['_dps_refund_policy'] ) ) {
            update_post_meta( $post_id, '_dps_refund_policy', wp_kses_post( $_POST['_dps_refund_policy'] ) );
        }

        $rates = array();

        if ( isset( $_POST['_dps_to'] ) ) {
            foreach ($_POST['_dps_to'] as $key => $value) {
                $country = $value;
                $price = floatval( $_POST['_dps_to_price'][$key] );

                if ( !empty( $value ) ) {
                    $rates[$country] = $price;
                }
            }
        }

        update_post_meta( $post_id, '_dps_rates', $rates );
    }

    /**
     * Include frontend post form
     *
     * @global type $post
     */
    function frontend_option() {
        global $post;

        $option = get_option( 'woocommerce_dokan_per_product_settings', array() );

        if ( isset( $option['enabled']) && $option['enabled'] == 'yes' ) {
            include_once dirname( __FILE__ ) . '/includes/frontend.php';
        }
    }

    /**
     * Generate country dropdwon
     *
     * @param array $options
     * @param string $selected
     * @param bool $everywhere
     */
    function country_dropdown( $options, $selected = '', $everywhere = false ) {
        printf( '<option value="">%s</option>', __( '- Select a location -', 'dokan-shipping' ) );

        if ( $everywhere ) {
            echo '<optgroup label="--------------------------">';
            printf( '<option value="everywhere"%s>%s</a>', selected( $selected, 'everywhere', true ), __( 'Everywhere Else', 'dokan-shipping' ) );
            echo '</optgroup>';
        }

        echo '<optgroup label="------------------------------">';
        foreach ($options as $key => $value) {
            printf( '<option value="%s"%s>%s</a>', $key, selected( $selected, $key, true ), $value );
        }
        echo '</optgroup>';
    }

    /**
     * Processing time dropdown options
     *
     * @return array
     */
    function get_processing_times() {
        $times = array(
            '' => __( 'Ready to ship in...', 'dokan-shipping' ),
            '1' => __( '1 business day', 'dokan-shipping' ),
            '2' => __( '1-2 business day', 'dokan-shipping' ),
            '3' => __( '1-3 business day', 'dokan-shipping' ),
            '4' => __( '3-5 business day', 'dokan-shipping' ),
            '5' => __( '1-2 weeks', 'dokan-shipping' ),
            '6' => __( '2-3 weeks', 'dokan-shipping' ),
            '7' => __( '3-4 weeks', 'dokan-shipping' ),
            '8' => __( '4-6 weeks', 'dokan-shipping' ),
            '9' => __( '6-8 weeks', 'dokan-shipping' ),
        );

        return apply_filters( 'dps_processing_times', $times );
    }

    /**
     * Get a single processing time string
     *
     * @param string $index
     * @return string
     */
    function get_processing_time( $index ) {
        $times = $this->get_processing_times();

        if ( isset( $times[$index] ) ) {
            return $times[$index];
        }
    }

    /**
     * Adds a seller tab in product single page
     *
     * @param array $tabs
     * @return array
     */
    function register_product_tab( $tabs ) {
        global $post;

        $enabled = get_post_meta( $post->ID, '_dps_ship_enable', true );
        if ( $enabled != 'yes' ) {
            return $tabs;
        }

        $tabs['shipping'] = array(
            'title' => __( 'Shipping', 'dokan-shipping' ),
            'priority' => 12,
            'callback' => array($this, 'shipping_tab')
        );

        return $tabs;
    }

    function shipping_tab() {
        global $post;

        $processing = get_post_meta( $post->ID, '_dps_pt', true );
        $from = get_post_meta( $post->ID, '_dps_from', true );
        $rates = get_post_meta( $post->ID, '_dps_rates', true );
        $shipping_policy = get_post_meta( $post->ID, '_dps_ship_policy', true );
        $refund_policy = get_post_meta( $post->ID, '_dps_refund_policy', true );

        $country_obj = new WC_Countries();
        $countries = $country_obj->countries;
        ?>

        <?php if ( $processing ) { ?>
                <p>
                    <strong>
                    <?php _e( 'Ready to ship in', 'dokan-shipping' ); ?> <?php echo $this->get_processing_time( $processing ); ?>

                    <?php
                    if ( $from ) {
                        echo __( 'from', 'dokan-shipping' ) . ' ' . $countries[$from];
                    }
                    ?>
                </strong>
            </p>
        <?php } ?>

        <?php if ( $rates ) { ?>
            <table class="table">
                <thead>
                    <tr>
                        <th><?php _e( 'Ship To', 'dokan-shipping' ); ?></th>
                        <th><?php _e( 'Cost', 'dokan-shipping' ); ?></th>
                    </tr>
                </thead>
                <thead>

                <?php foreach ($rates as $country => $cost) { ?>
                    <tr>
                        <td>
                            <?php
                            if ( $country == 'everywhere' ) {
                                _e( 'Everywhere Else', 'dokan-shipping' );
                            } else {
                                echo $countries[$country];
                            }
                            ?>
                        </td>
                        <td><?php echo wc_price( $cost ); ?></td>
                    </tr>
                <?php } ?>
                    </thead>
                </table>

            <?php } ?>

            <p>&nbsp;</p>

        <?php if ( $shipping_policy ) { ?>
            <strong><?php _e( 'Shipping Policy', 'dokan-shipping' ); ?></strong>
            <hr>

            <?php echo wpautop( $shipping_policy ); ?>
        <?php } ?>

        <p>&nbsp;</p>

        <?php if ( $refund_policy ) { ?>
            <strong><?php _e( 'Refund Policy', 'dokan-shipping' ); ?></strong>
            <hr>

            <?php echo wpautop( $refund_policy ); ?>
        <?php } ?>
        <?php
    }

    /**
     * Validate the shipping area
     *
     * @param  array $posted
     * @return void
     */
    function validate_country( $posted ) {
        // print_r($posted);

        $shipping_method = WC()->session->get( 'chosen_shipping_methods' );

        // per product shipping was not chosen
        //if ( ! is_array( $shipping_method ) || !in_array( 'dokan_per_product', $shipping_method ) ) {
//            wc_add_notice( __( 'shipping was not chosen', 'woocommerce' ), 'error' );
//            return;
//        }

        if ( isset( $posted['ship_to_different_address'] ) && $posted['ship_to_different_address'] == '1' ) {
            $shipping_country = $posted['shipping_country'];
        } else {
            $shipping_country = $posted['billing_country'];
        }

        // echo $shipping_country;
        $packages = WC()->shipping->get_packages();
        $packages = reset( $packages );

        if ( !isset( $packages['contents'] ) ) {
            return;
        }

        $products = $packages['contents'];
        $destination = isset( $packages['destination']['country'] ) ? $packages['destination']['country'] : '';

        $errors = array();
        foreach ($products as $key => $product) {
            if ( ! Dokan_WC_Per_Product_Shipping::is_product_enabled( $product['product_id'] ) ) {
                continue;
            }

            $cost = Dokan_WC_Per_Product_Shipping::get_product_costs( $product['product_id'] );
            $has_found = false;

            if ( array_key_exists( $destination, $cost ) ) {
                // for countries
                $has_found = true;

            } elseif ( array_key_exists( 'everywhere', $cost ) ) {
                // for everywhere
                $has_found = true;
            }

            if ( ! $has_found ) {
                $errors[] = sprintf( '<a href="%s">%s</a>', get_permalink( $product['product_id'] ), get_the_title( $product['product_id'] ) );
            }
        }

        if ( $errors ) {
            if ( count( $errors ) == 1 ) {
                $message = sprintf( __( 'This product does not ship to your chosen location: %s'), implode( ', ', $errors ) );
            } else {
                $message = sprintf( __( 'These products do not ship to your chosen location.: %s'), implode( ', ', $errors ) );
            }

            wc_add_notice( $message, 'error' );
        }
    }

} // Dokan_Per_Product_Shipping

$dokan_shipping = Dokan_Per_Product_Shipping::init();

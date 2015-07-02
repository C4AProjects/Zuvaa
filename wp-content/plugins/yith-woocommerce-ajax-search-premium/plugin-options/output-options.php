<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly


return array(

    'output' => array(
        'search_output_options'         => array(
            'title' => __( 'Output Options', 'yit' ),
            'type' => 'title',
            'id' => 'yith_wcas_output_options'
        ),

        'search_show_thumbnail' => array(
            'title'           => __( 'Show thumbnail', 'yit' ),
            'desc'            => __( 'Choose if you want show thumbnail and position', 'yit' ),
            'id'              => 'yith_wcas_show_thumbnail',
            'class'    => 'yith-wcas-chosen',
            'default'  => 'left',
            'type'     => 'select',
            'options'  => array(
                'none'  => __( 'Hide thumbnail', 'yit' ),
                'left'  => __( 'Show on the Left', 'yit' ),
                'right' => __( 'Show on the Right', 'yit' ),
            ),
        ),

        //from 1.2.6
        'search_show_thumbnail_dim' => array(
            'name'    => __( 'Size of thumbnails', 'yit' ),
            'id'      => 'yith_wcas_search_show_thumbnail_dim',
            'default'           => '50',
            'css'               => 'width:50px;',
            'type'              => 'number',
            'custom_attributes' => array(
                'min'  => 5,
                'max'  => 150,
                'step' => 1
            )
        ),

        'search_show_price' => array(
            'name'    => __( 'Show price', 'yit' ),
            'desc'    => __( 'Show price of product' ),
            'id'      => 'yith_wcas_show_price',
            'default' => 'yes',
            'type'    => 'checkbox'
        ),


        'search_price_label' => array(
            'name'    => __( 'Price Label', 'yit' ),
            'desc'    => __( 'Show a label before the price' ),
            'id'      => 'yith_wcas_search_price_label',
            'default' => __('Price:', 'yit'),
            'type'    => 'text'
        ),


        'loader_image'           => array(
            'name'              => __( 'Loader', 'yit' ),
            'desc'              => __( 'Loader gif', 'yit' ),
            'id'                => 'yith_wcas_loader_url',
            'default'           => YITH_WCAS_ASSETS_IMAGES_URL.'preloader.gif',
            'type'              => 'yith_wcas_upload'
        ),

        'search_output_options_end' => array(
            'type' => 'sectionend',
            'id'   => 'yith_wcas_output_options_end'
        ),

        'search_badges_options'         => array(
            'title' => __( 'Sales and Features badges', 'yit' ),
            'type' => 'title',
            'id' => 'yith_wcas_badges_options'
        ),

        'search_show_sale_badge' => array(
            'name'    => __( 'Show sale badge', 'yit' ),
            'desc'    => __( 'Show sale badge if the product is on sale' ),
            'id'      => 'yith_wcas_show_sale_badge',
            'default' => 'yes',
            'type'    => 'checkbox'
        ),

        'search_show_sale_badge_bgcolor'             => array(
            'name'    => __( 'Sale badge background color', 'yit' ),
            'type'    => 'color',
            'desc'    => '',
            'id'      => 'yith_wcas_sale_badge_bgcolor',
            'default' => '#7eb742'
        ),

        'search_show_sale_badge_color'             => array(
            'name'    => __( 'Sale badge color', 'yit' ),
            'type'    => 'color',
            'desc'    => '',
            'id'      => 'yith_wcas_sale_badge_color',
            'default' => '#ffffff'
        ),

        'search_show_featured_badge' => array(
            'name'    => __( 'Show featured badge', 'yit' ),
            'desc'    => '',
            'id'      => 'yith_wcas_show_featured_badge',
            'default' => 'yes',
            'type'    => 'checkbox'
        ),

        'search_show_featured_badge_bgcolor'             => array(
            'name'    => __( 'Featured badge background color', 'yit' ),
            'type'    => 'color',
            'desc'    => '',
            'id'      => 'yith_wcas_featured_badge_bgcolor',
            'default' => '#c0392b'
        ),

        'search_show_featured_badge_color'             => array(
            'name'    => __( 'Featured badge color', 'yit' ),
            'type'    => 'color',
            'desc'    => '',
            'id'      => 'yith_wcas_featured_badge_color',
            'default' => '#ffffff'
        ),

        //from 1.2.6
        'hide_feature_if_on_sale' => array(
            'name'    => __( 'Hide featured bagde if the product is on sale', 'yit' ),
            'desc'    => '',
            'id'      => 'yith_wcas_hide_feature_if_on_sale',
            'default' => 'no',
            'type'    => 'checkbox'
        ),

        'search_badges_options_end' => array(
            'type' => 'sectionend',
            'id'   => 'yith_wcas_badges_options_end'
        ),

        'search_excerpt_options'         => array(
            'title' => __( 'Title & Excerpt', 'yit' ),
            'type' => 'title',
            'id' => 'yith_wcas_excerpt_options'
        ),

        'search_title_color'             => array(
            'name'    => __( 'Title color', 'yit' ),
            'type'    => 'color',
            'desc'    => '',
            'id'      => 'yith_wcas_search_title_color',
            'default' => '#004b91'
        ),

        'search_show_excerpt' => array(
            'name'    => __( 'Show excerpt', 'yit' ),
            'desc'    => __( 'Show excerpt of product' ),
            'id'      => 'yith_wcas_show_excerpt',
            'default' => 'no',
            'type'    => 'checkbox'
        ),

        'search_show_excerpt_num_words' => array(
            'name'    => __( 'Number of words to show in excerpt', 'yit' ),
            'id'      => 'yith_wcas_show_excerpt_num_words',
            'default'           => '10',
            'css'               => 'width:50px;',
            'type'              => 'number',
            'custom_attributes' => array(
                'min'  => 5,
                'max'  => 100,
                'step' => 1
            )
        ),

        'search_excerpt_options_end' => array(
            'type' => 'sectionend',
            'id'   => 'yith_wcas_search_excerpt_options_end'
        ),

        'search_view_all_options'         => array(
            'title' => __( '"View All" Link', 'yit' ),
            'type' => 'title',
            'id' => 'yith_search_view_all_options'
        ),


        //from 1.2.6
        'search_show_view_all' => array(
            'name'    => __( 'Show "view all" link', 'yit' ),
            'desc'    => __( 'Add a link to the bottom of results' ),
            'id'      => 'yith_wcas_search_show_view_all',
            'default' => 'no',
            'type'    => 'checkbox'
        ),

        //from 1.2.6
        'search_show_view_all_text' => array(
            'name'    => __( 'Test of "view all" link', 'yit' ),
            'desc'    => __( 'Add a link at the bottom of results' ),
            'id'      => 'yith_wcas_search_show_view_all_text',
            'default' => __('View all', 'yit'),
            'type'    => 'text'
        ),

        'search_view_all_options_end' => array(
            'type' => 'sectionend',
            'id'   => 'yith_wcas_search_view_all_options_end'
        ),


    ),
);
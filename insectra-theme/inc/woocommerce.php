<?php
/**
 * WooCommerce integration tweaks.
 *
 * @package Insectra
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// Declare support and image sizes.
add_action( 'after_setup_theme', function() {
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 400,
        'single_image_width'    => 700,
        'product_grid'          => array(
            'default_rows'    => 4,
            'min_rows'        => 1,
            'default_columns' => 3,
            'min_columns'     => 1,
            'max_columns'     => 6,
        ),
    ) );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
} );

// Header cart count.
function insectra_woo_cart_link() {
    if ( ! function_exists( 'WC' ) ) return;
    $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    $url   = wc_get_cart_url();
    printf( '<a class="ins-cart-link" href="%s" title="%s"><i class="fa-solid fa-cart-shopping"></i><span class="count">%d</span></a>',
        esc_url( $url ),
        esc_attr__( 'View cart', 'insectra' ),
        intval( $count )
    );
}

// Update cart count via fragment refresh.
add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ) {
    ob_start(); insectra_woo_cart_link(); $fragments['a.ins-cart-link'] = ob_get_clean();
    return $fragments;
} );

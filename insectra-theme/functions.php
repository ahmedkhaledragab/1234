<?php
/**
 * Insectra functions and definitions.
 *
 * @package Insectra
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'INSECTRA_VERSION', '1.0.0' );
define( 'INSECTRA_DIR', get_template_directory() );
define( 'INSECTRA_URI', get_template_directory_uri() );

/**
 * Theme setup.
 */
function insectra_setup() {
    // i18n
    load_theme_textdomain( 'insectra', INSECTRA_DIR . '/languages' );

    // Core supports
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    add_theme_support( 'html5', array( 'search-form','comment-form','comment-list','gallery','caption','style','script' ) );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'woocommerce' );

    // Elementor support
    add_theme_support( 'elementor' );
    add_theme_support( 'elementor-pro' );

    // Menus
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'insectra' ),
        'footer'  => __( 'Footer Menu', 'insectra' ),
        'mobile'  => __( 'Mobile Menu', 'insectra' ),
    ) );

    // Image sizes
    add_image_size( 'insectra-service', 600, 420, true );
    add_image_size( 'insectra-team',    400, 480, true );
    add_image_size( 'insectra-blog',    720, 480, true );
    add_image_size( 'insectra-hero',    1920, 900, true );
}
add_action( 'after_setup_theme', 'insectra_setup' );

/**
 * Content width.
 */
function insectra_content_width() {
    $GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'insectra_content_width', 0 );

/**
 * Enqueue assets.
 */
function insectra_assets() {
    // Google Fonts (Inter + Cairo for Arabic).
    wp_enqueue_style(
        'insectra-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Cairo:wght@400;500;600;700;800&display=swap',
        array(), null
    );

    // Icon font (lightweight CDN; can be swapped to local).
    wp_enqueue_style(
        'insectra-icons',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
        array(), '6.5.0'
    );

    wp_enqueue_style( 'insectra-main', INSECTRA_URI . '/assets/css/main.css', array(), INSECTRA_VERSION );

    if ( is_rtl() ) {
        wp_style_add_data( 'insectra-main', 'rtl', 'replace' );
    }

    wp_enqueue_script( 'insectra-main', INSECTRA_URI . '/assets/js/main.js', array(), INSECTRA_VERSION, true );
    wp_localize_script( 'insectra-main', 'INSECTRA', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'insectra_nonce' ),
        'rtl'     => is_rtl(),
    ) );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'insectra_assets' );

/**
 * Sidebars / Widget areas.
 */
function insectra_widgets() {
    register_sidebar( array(
        'name'          => __( 'Blog Sidebar', 'insectra' ),
        'id'            => 'blog-sidebar',
        'description'   => __( 'Sidebar for blog pages.', 'insectra' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    for ( $i = 1; $i <= 4; $i++ ) {
        register_sidebar( array(
            'name'          => sprintf( __( 'Footer Column %d', 'insectra' ), $i ),
            'id'            => 'footer-' . $i,
            'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h5 class="widget-title">',
            'after_title'   => '</h5>',
        ) );
    }
}
add_action( 'widgets_init', 'insectra_widgets' );

/**
 * Body classes.
 */
function insectra_body_classes( $classes ) {
    if ( is_rtl() ) { $classes[] = 'is-rtl'; }
    $classes[] = 'insectra-theme';
    return $classes;
}
add_filter( 'body_class', 'insectra_body_classes' );

/**
 * Includes.
 */
require_once INSECTRA_DIR . '/inc/customizer.php';
require_once INSECTRA_DIR . '/inc/cpt.php';
require_once INSECTRA_DIR . '/inc/elementor.php';
require_once INSECTRA_DIR . '/inc/template-tags.php';
require_once INSECTRA_DIR . '/inc/walker-nav.php';
require_once INSECTRA_DIR . '/inc/tgm/tgm-init.php';

/**
 * Multilingual: Polylang & WPML compatibility helpers.
 */
function insectra_register_strings() {
    if ( function_exists( 'pll_register_string' ) ) {
        pll_register_string( 'site_phone',   get_theme_mod( 'insectra_phone',   '+1 (800) 555-1234' ),  'Insectra' );
        pll_register_string( 'site_email',   get_theme_mod( 'insectra_email',   'info@insectra.com' ),  'Insectra' );
        pll_register_string( 'site_address', get_theme_mod( 'insectra_address', '123 Pest Control St.' ),'Insectra' );
        pll_register_string( 'hero_title',   get_theme_mod( 'insectra_hero_title',    __( 'Safe & Reliable Pest Control Service', 'insectra' ) ), 'Insectra' );
        pll_register_string( 'hero_subtitle',get_theme_mod( 'insectra_hero_subtitle', __( 'We protect your home and business with eco-friendly treatments.', 'insectra' ) ), 'Insectra' );
    }
}
add_action( 'init', 'insectra_register_strings' );

/**
 * Helper to get translated theme mod (Polylang/WPML aware).
 */
function insectra_tr( $key, $default = '' ) {
    $value = get_theme_mod( $key, $default );
    if ( function_exists( 'pll__' ) )      { $value = pll__( $value ); }
    elseif ( function_exists( 'icl_t' ) )  { $value = apply_filters( 'wpml_translate_single_string', $value, 'Insectra', $key ); }
    return $value;
}

/**
 * Excerpt length & more.
 */
add_filter( 'excerpt_length', function() { return 22; }, 999 );
add_filter( 'excerpt_more',   function() { return '…'; } );

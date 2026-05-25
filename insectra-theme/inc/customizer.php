<?php
/**
 * Customizer settings.
 *
 * @package Insectra
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function insectra_customize_register( $wp_customize ) {

    // ===== Brand Colors =====
    $wp_customize->add_section( 'insectra_colors', array(
        'title' => __( 'Insectra: Brand Colors', 'insectra' ), 'priority' => 30,
    ) );

    $colors = array(
        'insectra_color_primary'      => array( 'label' => __( 'Primary',      'insectra' ), 'default' => '#1FAE52' ),
        'insectra_color_primary_dark' => array( 'label' => __( 'Primary Dark', 'insectra' ), 'default' => '#0F8A3E' ),
        'insectra_color_dark'         => array( 'label' => __( 'Dark',         'insectra' ), 'default' => '#0E1B2C' ),
        'insectra_color_accent'       => array( 'label' => __( 'Accent',       'insectra' ), 'default' => '#FFB400' ),
    );
    foreach ( $colors as $key => $cfg ) {
        $wp_customize->add_setting( $key, array( 'default' => $cfg['default'], 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'refresh' ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, array( 'label' => $cfg['label'], 'section' => 'insectra_colors' ) ) );
    }

    // ===== Contact Info =====
    $wp_customize->add_section( 'insectra_contact', array( 'title' => __( 'Insectra: Contact Info', 'insectra' ), 'priority' => 35 ) );

    $contact = array(
        'insectra_phone'   => array( 'label' => __( 'Phone',   'insectra' ), 'default' => '+1 (800) 555-1234' ),
        'insectra_email'   => array( 'label' => __( 'Email',   'insectra' ), 'default' => 'info@insectra.com' ),
        'insectra_address' => array( 'label' => __( 'Address', 'insectra' ), 'default' => '123 Pest Control St., NY' ),
        'insectra_hours'   => array( 'label' => __( 'Hours',   'insectra' ), 'default' => __( 'Mon - Sat: 8:00 - 18:00', 'insectra' ) ),
    );
    foreach ( $contact as $key => $cfg ) {
        $wp_customize->add_setting( $key, array( 'default' => $cfg['default'], 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( $key, array( 'label' => $cfg['label'], 'section' => 'insectra_contact', 'type' => 'text' ) );
    }

    // ===== Header / CTA =====
    $wp_customize->add_section( 'insectra_cta', array( 'title' => __( 'Insectra: Header CTA', 'insectra' ), 'priority' => 40 ) );

    $wp_customize->add_setting( 'insectra_cta_label', array( 'default' => __( 'Get a Quote', 'insectra' ), 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'insectra_cta_label', array( 'label' => __( 'CTA Label', 'insectra' ), 'section' => 'insectra_cta' ) );
    $wp_customize->add_setting( 'insectra_cta_url', array( 'default' => '#contact', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( 'insectra_cta_url', array( 'label' => __( 'CTA URL', 'insectra' ), 'section' => 'insectra_cta', 'type' => 'url' ) );

    // ===== Hero =====
    $wp_customize->add_section( 'insectra_hero', array( 'title' => __( 'Insectra: Homepage Hero', 'insectra' ), 'priority' => 45 ) );

    $wp_customize->add_setting( 'insectra_hero_eyebrow', array( 'default' => __( 'Pest Control Experts', 'insectra' ), 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'insectra_hero_eyebrow', array( 'label' => __( 'Eyebrow', 'insectra' ), 'section' => 'insectra_hero' ) );
    $wp_customize->add_setting( 'insectra_hero_title', array( 'default' => __( 'Safe & Reliable Pest Control Service', 'insectra' ), 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'insectra_hero_title', array( 'label' => __( 'Title', 'insectra' ), 'section' => 'insectra_hero' ) );
    $wp_customize->add_setting( 'insectra_hero_subtitle', array( 'default' => __( 'We protect your home and business with eco-friendly treatments by certified specialists.', 'insectra' ), 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'insectra_hero_subtitle', array( 'label' => __( 'Subtitle', 'insectra' ), 'section' => 'insectra_hero', 'type' => 'textarea' ) );

    $wp_customize->add_setting( 'insectra_hero_image', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'insectra_hero_image', array( 'label' => __( 'Hero Image', 'insectra' ), 'section' => 'insectra_hero' ) ) );

    // ===== Socials =====
    $wp_customize->add_section( 'insectra_socials', array( 'title' => __( 'Insectra: Social Links', 'insectra' ), 'priority' => 50 ) );
    foreach ( array( 'facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'whatsapp' ) as $s ) {
        $key = 'insectra_social_' . $s;
        $wp_customize->add_setting( $key, array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
        $wp_customize->add_control( $key, array( 'label' => ucfirst( $s ), 'section' => 'insectra_socials', 'type' => 'url' ) );
    }

    // ===== Footer =====
    $wp_customize->add_section( 'insectra_footer', array( 'title' => __( 'Insectra: Footer', 'insectra' ), 'priority' => 55 ) );
    $wp_customize->add_setting( 'insectra_footer_about', array( 'default' => __( 'We deliver safe, eco-friendly pest control for homes and businesses.', 'insectra' ), 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'insectra_footer_about', array( 'label' => __( 'About text', 'insectra' ), 'section' => 'insectra_footer', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'insectra_footer_cta', array( 'default' => __( 'Need professional pest control? Book a free inspection today.', 'insectra' ), 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'insectra_footer_cta', array( 'label' => __( 'Footer CTA Headline', 'insectra' ), 'section' => 'insectra_footer' ) );

    // ===== Map =====
    $wp_customize->add_section( 'insectra_map', array( 'title' => __( 'Insectra: Map Embed', 'insectra' ), 'priority' => 60 ) );
    $wp_customize->add_setting( 'insectra_map_embed', array( 'default' => '', 'sanitize_callback' => 'wp_kses_post' ) );
    $wp_customize->add_control( 'insectra_map_embed', array(
        'label' => __( 'Google Maps iframe HTML', 'insectra' ),
        'description' => __( 'Paste an iframe embed from Google Maps. Leave empty for default.', 'insectra' ),
        'section' => 'insectra_map', 'type' => 'textarea',
    ) );
}
add_action( 'customize_register', 'insectra_customize_register' );

/**
 * Output dynamic CSS variables from Customizer colors.
 */
function insectra_customizer_inline_css() {
    $primary      = get_theme_mod( 'insectra_color_primary',      '#1FAE52' );
    $primary_dark = get_theme_mod( 'insectra_color_primary_dark', '#0F8A3E' );
    $dark         = get_theme_mod( 'insectra_color_dark',         '#0E1B2C' );
    $accent       = get_theme_mod( 'insectra_color_accent',       '#FFB400' );
    $css = ":root{--ins-primary:{$primary};--ins-primary-dark:{$primary_dark};--ins-dark:{$dark};--ins-accent:{$accent};}";
    wp_add_inline_style( 'insectra-main', $css );
}
add_action( 'wp_enqueue_scripts', 'insectra_customizer_inline_css', 20 );

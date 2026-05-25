<?php
/**
 * Template tags / helpers.
 *
 * @package Insectra
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function insectra_default_menu() {
    echo '<ul class="menu">';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'insectra' ) . '</a></li>';
    echo '<li><a href="#about">' . esc_html__( 'About', 'insectra' ) . '</a></li>';
    echo '<li><a href="#services">' . esc_html__( 'Services', 'insectra' ) . '</a></li>';
    echo '<li><a href="#projects">' . esc_html__( 'Projects', 'insectra' ) . '</a></li>';
    echo '<li><a href="#pricing">' . esc_html__( 'Pricing', 'insectra' ) . '</a></li>';
    echo '<li><a href="#blog">' . esc_html__( 'Blog', 'insectra' ) . '</a></li>';
    echo '<li><a href="#contact">' . esc_html__( 'Contact', 'insectra' ) . '</a></li>';
    echo '</ul>';
}

function insectra_get_socials() {
    return array(
        'facebook'  => get_theme_mod( 'insectra_social_facebook',  '' ),
        'twitter'   => get_theme_mod( 'insectra_social_twitter',   '' ),
        'instagram' => get_theme_mod( 'insectra_social_instagram', '' ),
        'linkedin'  => get_theme_mod( 'insectra_social_linkedin',  '' ),
        'youtube'   => get_theme_mod( 'insectra_social_youtube',   '' ),
        'whatsapp'  => get_theme_mod( 'insectra_social_whatsapp',  '' ),
    );
}

/**
 * Detect if the current post/page is built with Elementor.
 * Returns true if Elementor builder has data so theme templates can defer to the_content().
 */
function insectra_is_built_with_elementor( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    if ( ! $post_id || ! class_exists( '\Elementor\Plugin' ) ) return false;
    $document = \Elementor\Plugin::$instance->documents->get( $post_id );
    return $document && $document->is_built_with_elementor();
}

/**
 * Render Elementor-first wrapper: if the page is built in Elementor, output the_content().
 * Otherwise call $fallback (closure) to render the prebuilt sections.
 */
function insectra_elementor_first( callable $fallback ) {
    if ( insectra_is_built_with_elementor() ) {
        while ( have_posts() ) { the_post(); the_content(); }
        return;
    }
    $fallback();
}

/**
 * Language switcher.
 */
function insectra_lang_switcher() {
    if ( function_exists( 'pll_the_languages' ) ) {
        echo '<li class="lang-switcher">';
        pll_the_languages( array( 'show_flags' => 0, 'show_names' => 1, 'display_names_as' => 'slug' ) );
        echo '</li>';
        return;
    }
    if ( function_exists( 'icl_get_languages' ) ) {
        $langs = icl_get_languages( 'skip_missing=0' );
        if ( $langs ) {
            echo '<li class="lang-switcher"><ul>';
            foreach ( $langs as $l ) {
                printf( '<li><a href="%s">%s</a></li>', esc_url( $l['url'] ), esc_html( strtoupper( $l['language_code'] ) ) );
            }
            echo '</ul></li>';
        }
        return;
    }
    echo '<li class="lang-switcher static"><a href="?lang=en">EN</a><span>|</span><a href="?lang=ar">AR</a></li>';
}

/**
 * Breadcrumbs.
 */
function insectra_breadcrumbs() {
    if ( function_exists( 'yoast_breadcrumb' ) ) { yoast_breadcrumb( '<p class="ins-bc">', '</p>' ); return; }
    $sep = ' <i class="fa-solid fa-chevron-right"></i> ';
    echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'insectra' ) . '</a>';
    if ( is_singular() ) {
        $pt = get_post_type_object( get_post_type() );
        if ( $pt && $pt->has_archive ) {
            echo $sep . '<a href="' . esc_url( get_post_type_archive_link( get_post_type() ) ) . '">' . esc_html( $pt->labels->name ) . '</a>';
        }
        echo $sep . '<span>' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_archive() ) {
        echo $sep . '<span>' . wp_kses_post( get_the_archive_title() ) . '</span>';
    } elseif ( is_search() ) {
        echo $sep . '<span>' . esc_html__( 'Search', 'insectra' ) . '</span>';
    } elseif ( is_404() ) {
        echo $sep . '<span>404</span>';
    }
}

/**
 * Star rating helper.
 */
function insectra_stars( $rating = 5 ) {
    $rating = max( 0, min( 5, (int) $rating ) );
    $out = '<span class="stars" aria-label="' . esc_attr( sprintf( __( '%d out of 5', 'insectra' ), $rating ) ) . '">';
    for ( $i = 1; $i <= 5; $i++ ) {
        $out .= '<i class="fa-' . ( $i <= $rating ? 'solid' : 'regular' ) . ' fa-star"></i>';
    }
    $out .= '</span>';
    return $out;
}

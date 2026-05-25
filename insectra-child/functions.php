<?php
/**
 * Insectra Child Theme functions.
 *
 * @package Insectra_Child
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Enqueue parent + child stylesheets.
 */
function insectra_child_enqueue() {
    wp_enqueue_style( 'insectra-parent', get_template_directory_uri() . '/style.css', array(), '1.0.0' );
    wp_enqueue_style( 'insectra-child',  get_stylesheet_uri(), array( 'insectra-parent', 'insectra-main' ), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'insectra_child_enqueue', 20 );

/**
 * Add your custom code below this line.
 *
 * Examples:
 *
 * // Override an Insectra string
 * add_filter( 'gettext', function( $translated, $original, $domain ) {
 *     if ( $domain === 'insectra' && $original === 'Get a Quote' ) {
 *         return 'Request Service';
 *     }
 *     return $translated;
 * }, 20, 3 );
 */

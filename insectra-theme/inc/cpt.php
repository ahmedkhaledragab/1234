<?php
/**
 * Custom Post Types: Services, Team, Testimonials, Pricing.
 *
 * @package Insectra
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function insectra_register_cpts() {

    register_post_type( 'ins_service', array(
        'labels' => array(
            'name'          => __( 'Services',   'insectra' ),
            'singular_name' => __( 'Service',    'insectra' ),
            'add_new_item'  => __( 'Add New Service', 'insectra' ),
            'edit_item'     => __( 'Edit Service',    'insectra' ),
        ),
        'public'       => true,
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-shield',
        'rewrite'      => array( 'slug' => 'services' ),
        'supports'     => array( 'title','editor','thumbnail','excerpt','elementor' ),
        'show_in_rest' => true,
    ) );

    register_post_type( 'ins_team', array(
        'labels' => array( 'name' => __( 'Team', 'insectra' ), 'singular_name' => __( 'Team Member', 'insectra' ) ),
        'public'       => true,
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-groups',
        'rewrite'      => array( 'slug' => 'team' ),
        'supports'     => array( 'title','editor','thumbnail','elementor' ),
        'show_in_rest' => true,
    ) );

    register_post_type( 'ins_testimonial', array(
        'labels' => array( 'name' => __( 'Testimonials', 'insectra' ), 'singular_name' => __( 'Testimonial', 'insectra' ) ),
        'public'       => false,
        'show_ui'      => true,
        'menu_icon'    => 'dashicons-format-quote',
        'supports'     => array( 'title','editor','thumbnail' ),
        'show_in_rest' => true,
    ) );

    register_post_type( 'ins_pricing', array(
        'labels' => array( 'name' => __( 'Pricing Plans', 'insectra' ), 'singular_name' => __( 'Pricing Plan', 'insectra' ) ),
        'public'       => false,
        'show_ui'      => true,
        'menu_icon'    => 'dashicons-tag',
        'supports'     => array( 'title','editor' ),
        'show_in_rest' => true,
    ) );

    register_taxonomy( 'ins_service_cat', 'ins_service', array(
        'labels' => array( 'name' => __( 'Service Categories', 'insectra' ) ),
        'hierarchical' => true, 'show_in_rest' => true,
    ) );
}
add_action( 'init', 'insectra_register_cpts' );

/**
 * Simple meta boxes for testimonials & pricing & team.
 */
function insectra_add_meta_boxes() {
    add_meta_box( 'ins_testimonial_meta', __( 'Testimonial Details', 'insectra' ), 'insectra_testimonial_meta', 'ins_testimonial' );
    add_meta_box( 'ins_pricing_meta',     __( 'Plan Details',        'insectra' ), 'insectra_pricing_meta',     'ins_pricing' );
    add_meta_box( 'ins_team_meta',        __( 'Member Details',      'insectra' ), 'insectra_team_meta',        'ins_team' );
    add_meta_box( 'ins_service_meta',     __( 'Service Details',     'insectra' ), 'insectra_service_meta',     'ins_service' );
}
add_action( 'add_meta_boxes', 'insectra_add_meta_boxes' );

function insectra_text_field( $post, $key, $label, $type = 'text', $placeholder = '' ) {
    $val = get_post_meta( $post->ID, $key, true );
    echo '<p><label><strong>' . esc_html( $label ) . '</strong></label><br>';
    if ( $type === 'textarea' ) {
        printf( '<textarea name="%s" rows="3" style="width:100%%" placeholder="%s">%s</textarea>',
            esc_attr( $key ), esc_attr( $placeholder ), esc_textarea( $val ) );
    } else {
        printf( '<input type="%s" name="%s" value="%s" style="width:100%%" placeholder="%s">',
            esc_attr( $type ), esc_attr( $key ), esc_attr( $val ), esc_attr( $placeholder ) );
    }
    echo '</p>';
}

function insectra_testimonial_meta( $post ) {
    wp_nonce_field( 'insectra_meta', 'insectra_meta_nonce' );
    insectra_text_field( $post, '_ins_role',   __( 'Author Role', 'insectra' ) );
    insectra_text_field( $post, '_ins_rating', __( 'Rating (1-5)', 'insectra' ), 'number' );
}

function insectra_pricing_meta( $post ) {
    wp_nonce_field( 'insectra_meta', 'insectra_meta_nonce' );
    insectra_text_field( $post, '_ins_price',    __( 'Price (e.g. 49)', 'insectra' ) );
    insectra_text_field( $post, '_ins_currency', __( 'Currency Symbol', 'insectra' ), 'text', '$' );
    insectra_text_field( $post, '_ins_period',   __( 'Period (e.g. /mo)', 'insectra' ) );
    insectra_text_field( $post, '_ins_features', __( 'Features (one per line)', 'insectra' ), 'textarea' );
    insectra_text_field( $post, '_ins_cta_url',  __( 'CTA URL', 'insectra' ), 'url' );
    insectra_text_field( $post, '_ins_featured', __( 'Featured? (1/0)', 'insectra' ), 'number' );
}

function insectra_team_meta( $post ) {
    wp_nonce_field( 'insectra_meta', 'insectra_meta_nonce' );
    insectra_text_field( $post, '_ins_role',     __( 'Position', 'insectra' ) );
    insectra_text_field( $post, '_ins_facebook', 'Facebook URL', 'url' );
    insectra_text_field( $post, '_ins_twitter',  'Twitter URL',  'url' );
    insectra_text_field( $post, '_ins_linkedin', 'LinkedIn URL', 'url' );
}

function insectra_service_meta( $post ) {
    wp_nonce_field( 'insectra_meta', 'insectra_meta_nonce' );
    insectra_text_field( $post, '_ins_icon', __( 'Font Awesome Icon (e.g. fa-solid fa-bug)', 'insectra' ) );
    insectra_text_field( $post, '_ins_short', __( 'Short Description', 'insectra' ), 'textarea' );
}

function insectra_save_meta( $post_id ) {
    if ( ! isset( $_POST['insectra_meta_nonce'] ) || ! wp_verify_nonce( $_POST['insectra_meta_nonce'], 'insectra_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $keys = array( '_ins_role','_ins_rating','_ins_price','_ins_currency','_ins_period','_ins_features','_ins_cta_url','_ins_featured','_ins_facebook','_ins_twitter','_ins_linkedin','_ins_icon','_ins_short' );
    foreach ( $keys as $k ) {
        if ( isset( $_POST[ $k ] ) ) {
            $val = is_string( $_POST[ $k ] ) ? wp_kses_post( wp_unslash( $_POST[ $k ] ) ) : '';
            update_post_meta( $post_id, $k, $val );
        }
    }
}
add_action( 'save_post', 'insectra_save_meta' );

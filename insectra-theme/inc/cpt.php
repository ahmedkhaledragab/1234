<?php
/**
 * Custom Post Types: Services, Staff, Testimonials, Projects, Pricing.
 *
 * @package Insectra
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function insectra_register_cpts() {

    // Services
    register_post_type( 'ins_service', array(
        'labels' => array(
            'name'          => __( 'Services',   'insectra' ),
            'singular_name' => __( 'Service',    'insectra' ),
            'add_new_item'  => __( 'Add New Service', 'insectra' ),
            'edit_item'     => __( 'Edit Service',    'insectra' ),
            'menu_name'     => __( 'Services',   'insectra' ),
        ),
        'public'       => true,
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-shield',
        'rewrite'      => array( 'slug' => 'services' ),
        'supports'     => array( 'title','editor','thumbnail','excerpt','elementor' ),
        'show_in_rest' => true,
    ) );

    // Staff (formerly Team)
    register_post_type( 'ins_staff', array(
        'labels' => array(
            'name'          => __( 'Staff',           'insectra' ),
            'singular_name' => __( 'Staff Member',    'insectra' ),
            'add_new_item'  => __( 'Add New Staff Member', 'insectra' ),
            'edit_item'     => __( 'Edit Staff Member',    'insectra' ),
            'menu_name'     => __( 'Staff',           'insectra' ),
        ),
        'public'       => true,
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-businessperson',
        'rewrite'      => array( 'slug' => 'staff' ),
        'supports'     => array( 'title','editor','thumbnail','elementor' ),
        'show_in_rest' => true,
    ) );

    // Backwards-compat alias for older content using ins_team
    register_post_type( 'ins_team', array(
        'labels' => array( 'name' => __( 'Team (legacy)', 'insectra' ) ),
        'public' => false, 'show_ui' => false,
    ) );

    // Projects
    register_post_type( 'ins_project', array(
        'labels' => array(
            'name'          => __( 'Projects',        'insectra' ),
            'singular_name' => __( 'Project',         'insectra' ),
            'add_new_item'  => __( 'Add New Project', 'insectra' ),
            'edit_item'     => __( 'Edit Project',    'insectra' ),
            'menu_name'     => __( 'Projects',        'insectra' ),
        ),
        'public'       => true,
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-portfolio',
        'rewrite'      => array( 'slug' => 'projects' ),
        'supports'     => array( 'title','editor','thumbnail','excerpt','elementor' ),
        'show_in_rest' => true,
    ) );

    // Testimonials
    register_post_type( 'ins_testimonial', array(
        'labels' => array(
            'name'          => __( 'Testimonials', 'insectra' ),
            'singular_name' => __( 'Testimonial',  'insectra' ),
        ),
        'public'       => false,
        'show_ui'      => true,
        'menu_icon'    => 'dashicons-format-quote',
        'supports'     => array( 'title','editor','thumbnail' ),
        'show_in_rest' => true,
    ) );

    // Pricing
    register_post_type( 'ins_pricing', array(
        'labels' => array(
            'name'          => __( 'Pricing Plans', 'insectra' ),
            'singular_name' => __( 'Pricing Plan',  'insectra' ),
        ),
        'public'       => false,
        'show_ui'      => true,
        'menu_icon'    => 'dashicons-tag',
        'supports'     => array( 'title','editor' ),
        'show_in_rest' => true,
    ) );

    // Taxonomies
    register_taxonomy( 'ins_service_cat', 'ins_service', array(
        'labels'       => array( 'name' => __( 'Service Categories', 'insectra' ) ),
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => array( 'slug' => 'service-category' ),
    ) );

    register_taxonomy( 'ins_project_cat', 'ins_project', array(
        'labels'       => array( 'name' => __( 'Project Categories', 'insectra' ) ),
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => array( 'slug' => 'project-category' ),
    ) );

    register_taxonomy( 'ins_staff_dept', 'ins_staff', array(
        'labels'       => array( 'name' => __( 'Departments', 'insectra' ) ),
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => array( 'slug' => 'department' ),
    ) );
}
add_action( 'init', 'insectra_register_cpts' );

/**
 * Meta boxes.
 */
function insectra_add_meta_boxes() {
    add_meta_box( 'ins_service_meta',     __( 'Service Details',     'insectra' ), 'insectra_service_meta',     'ins_service' );
    add_meta_box( 'ins_staff_meta',       __( 'Staff Details',       'insectra' ), 'insectra_staff_meta',       'ins_staff' );
    add_meta_box( 'ins_project_meta',     __( 'Project Details',     'insectra' ), 'insectra_project_meta',     'ins_project' );
    add_meta_box( 'ins_testimonial_meta', __( 'Testimonial Details', 'insectra' ), 'insectra_testimonial_meta', 'ins_testimonial' );
    add_meta_box( 'ins_pricing_meta',     __( 'Plan Details',        'insectra' ), 'insectra_pricing_meta',     'ins_pricing' );
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

function insectra_service_meta( $post ) {
    wp_nonce_field( 'insectra_meta', 'insectra_meta_nonce' );
    insectra_text_field( $post, '_ins_icon',       __( 'Font Awesome Icon (e.g. fa-solid fa-bug)', 'insectra' ) );
    insectra_text_field( $post, '_ins_short',      __( 'Short Description', 'insectra' ), 'textarea' );
    insectra_text_field( $post, '_ins_price_from', __( 'Starting Price (e.g. 49)', 'insectra' ) );
    insectra_text_field( $post, '_ins_duration',   __( 'Service Duration (e.g. 2-3 hours)', 'insectra' ) );
}

function insectra_staff_meta( $post ) {
    wp_nonce_field( 'insectra_meta', 'insectra_meta_nonce' );
    insectra_text_field( $post, '_ins_role',     __( 'Position', 'insectra' ) );
    insectra_text_field( $post, '_ins_phone',    __( 'Phone',    'insectra' ) );
    insectra_text_field( $post, '_ins_email',    __( 'Email',    'insectra' ), 'email' );
    insectra_text_field( $post, '_ins_facebook', 'Facebook URL', 'url' );
    insectra_text_field( $post, '_ins_twitter',  'Twitter URL',  'url' );
    insectra_text_field( $post, '_ins_linkedin', 'LinkedIn URL', 'url' );
    insectra_text_field( $post, '_ins_instagram','Instagram URL','url' );
}

function insectra_project_meta( $post ) {
    wp_nonce_field( 'insectra_meta', 'insectra_meta_nonce' );
    insectra_text_field( $post, '_ins_client',    __( 'Client',     'insectra' ) );
    insectra_text_field( $post, '_ins_location',  __( 'Location',   'insectra' ) );
    insectra_text_field( $post, '_ins_date',      __( 'Project Date','insectra' ) );
    insectra_text_field( $post, '_ins_duration',  __( 'Duration',   'insectra' ) );
    insectra_text_field( $post, '_ins_url',       __( 'Project URL','insectra' ), 'url' );
}

function insectra_testimonial_meta( $post ) {
    wp_nonce_field( 'insectra_meta', 'insectra_meta_nonce' );
    insectra_text_field( $post, '_ins_role',   __( 'Author Role',  'insectra' ) );
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

function insectra_save_meta( $post_id ) {
    if ( ! isset( $_POST['insectra_meta_nonce'] ) || ! wp_verify_nonce( $_POST['insectra_meta_nonce'], 'insectra_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $keys = array(
        '_ins_role','_ins_rating','_ins_price','_ins_currency','_ins_period','_ins_features',
        '_ins_cta_url','_ins_featured','_ins_facebook','_ins_twitter','_ins_linkedin','_ins_instagram',
        '_ins_icon','_ins_short','_ins_price_from','_ins_duration','_ins_phone','_ins_email',
        '_ins_client','_ins_location','_ins_date','_ins_url',
    );
    foreach ( $keys as $k ) {
        if ( isset( $_POST[ $k ] ) ) {
            $val = is_string( $_POST[ $k ] ) ? wp_kses_post( wp_unslash( $_POST[ $k ] ) ) : '';
            update_post_meta( $post_id, $k, $val );
        }
    }
}
add_action( 'save_post', 'insectra_save_meta' );

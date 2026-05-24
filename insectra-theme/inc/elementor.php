<?php
/**
 * Elementor support: register theme locations and add CPT support.
 *
 * @package Insectra
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register Elementor theme locations (used by Elementor Pro Theme Builder).
 */
function insectra_register_elementor_locations( $manager ) {
    $manager->register_all_core_location();
    $manager->register_location( 'before_content', array( 'label' => __( 'Before Content', 'insectra' ) ) );
    $manager->register_location( 'after_content',  array( 'label' => __( 'After Content', 'insectra' ) ) );
}
add_action( 'elementor/theme/register_locations', 'insectra_register_elementor_locations' );

/**
 * Enable Elementor for our CPTs (covered by support arg, but ensure setting flag too).
 */
function insectra_enable_elementor_for_cpts( $post_types ) {
    $post_types[] = 'ins_service';
    $post_types[] = 'ins_team';
    return $post_types;
}
add_filter( 'elementor/utils/get_public_post_types', 'insectra_enable_elementor_for_cpts' );

/**
 * Notice if Elementor is missing.
 */
function insectra_admin_notice_missing_elementor() {
    if ( ! current_user_can( 'activate_plugins' ) ) return;
    if ( did_action( 'elementor/loaded' ) ) return;
    echo '<div class="notice notice-warning is-dismissible"><p>';
    printf( esc_html__( 'Insectra theme works best with Elementor. %sInstall Elementor%s.', 'insectra' ), '<a href="' . esc_url( admin_url( 'plugin-install.php?s=elementor&tab=search' ) ) . '">', '</a>' );
    echo '</p></div>';
}
add_action( 'admin_notices', 'insectra_admin_notice_missing_elementor' );

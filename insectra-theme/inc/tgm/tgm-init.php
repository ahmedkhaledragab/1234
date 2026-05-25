<?php
/**
 * Recommend Elementor (and optional plugins) using a simple admin notice.
 * For full TGM integration, drop class-tgm-plugin-activation.php in this folder
 * and require it instead; this stub keeps the theme self-contained.
 *
 * @package Insectra
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function insectra_recommended_plugins_notice() {
    if ( ! current_user_can( 'install_plugins' ) ) return;
    $missing = array();
    if ( ! did_action( 'elementor/loaded' ) )           $missing[] = '<a href="' . esc_url( admin_url( 'plugin-install.php?s=elementor&tab=search' ) ) . '">Elementor</a>';
    if ( ! defined( 'CONTACT_FORM_7_VERSION' ) )        $missing[] = '<a href="' . esc_url( admin_url( 'plugin-install.php?s=contact+form+7&tab=search' ) ) . '">Contact Form 7</a>';
    if ( ! defined( 'POLYLANG_VERSION' ) && ! defined( 'ICL_SITEPRESS_VERSION' ) ) $missing[] = '<a href="' . esc_url( admin_url( 'plugin-install.php?s=polylang&tab=search' ) ) . '">Polylang</a>';
    if ( ! $missing ) return;
    echo '<div class="notice notice-info is-dismissible"><p><strong>Insectra:</strong> ';
    printf( esc_html__( 'Recommended plugins: %s', 'insectra' ), wp_kses_post( implode( ', ', $missing ) ) );
    echo '</p></div>';
}
add_action( 'admin_notices', 'insectra_recommended_plugins_notice' );

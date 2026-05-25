<?php
/**
 * Theme Options admin page (lightweight, native Settings API based).
 * For full Redux Framework integration the theme is compatible — Redux options will
 * be picked up automatically when the plugin is active. This file provides a simple
 * native fallback panel under "Theme Options" menu.
 *
 * @package Insectra
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class Insectra_Theme_Options {
    const OPTION = 'insectra_theme_options';

    public function __construct() {
        add_action( 'admin_menu',  array( $this, 'menu' ) );
        add_action( 'admin_init',  array( $this, 'register' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
    }

    public function menu() {
        add_menu_page(
            __( 'Theme Options', 'insectra' ),
            __( 'Theme Options', 'insectra' ),
            'manage_options',
            'insectra-theme-options',
            array( $this, 'render' ),
            'dashicons-admin-customizer',
            61
        );
    }

    public function register() {
        register_setting( 'insectra_theme_options_group', self::OPTION );

        add_settings_section( 'general',  __( 'General',  'insectra' ), '__return_false', 'insectra-theme-options' );
        add_settings_section( 'header',   __( 'Header',   'insectra' ), '__return_false', 'insectra-theme-options' );
        add_settings_section( 'footer',   __( 'Footer',   'insectra' ), '__return_false', 'insectra-theme-options' );
        add_settings_section( 'integrations', __( 'Integrations', 'insectra' ), '__return_false', 'insectra-theme-options' );

        $fields = array(
            'general' => array(
                'preloader_enable' => array( 'type' => 'checkbox', 'label' => __( 'Enable Preloader', 'insectra' ) ),
                'back_to_top'      => array( 'type' => 'checkbox', 'label' => __( 'Show "Back to Top" button', 'insectra' ), 'default' => 1 ),
                'sticky_header'    => array( 'type' => 'checkbox', 'label' => __( 'Sticky Header', 'insectra' ),    'default' => 1 ),
                'page_title_style' => array( 'type' => 'select',   'label' => __( 'Page Title Style', 'insectra' ),
                    'options' => array( 'default' => 'Default Banner', 'minimal' => 'Minimal', 'hidden' => 'Hidden' ) ),
            ),
            'header' => array(
                'header_layout'    => array( 'type' => 'select',   'label' => __( 'Header Layout', 'insectra' ),
                    'options' => array( 'classic' => 'Classic', 'centered' => 'Centered', 'transparent' => 'Transparent' ) ),
                'header_topbar'    => array( 'type' => 'checkbox', 'label' => __( 'Show Top Bar', 'insectra' ), 'default' => 1 ),
            ),
            'footer' => array(
                'footer_layout'    => array( 'type' => 'select',   'label' => __( 'Footer Layout', 'insectra' ),
                    'options' => array( '4col' => '4 Columns', '3col' => '3 Columns', 'simple' => 'Simple' ) ),
                'footer_cta'       => array( 'type' => 'checkbox', 'label' => __( 'Show Footer CTA Bar', 'insectra' ), 'default' => 1 ),
                'copyright_text'   => array( 'type' => 'text',     'label' => __( 'Copyright Text', 'insectra' ),
                    'default' => '© ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '. All rights reserved.' ),
            ),
            'integrations' => array(
                'mailchimp_action' => array( 'type' => 'url',      'label' => __( 'MailChimp Form Action URL', 'insectra' ) ),
                'recaptcha_site'   => array( 'type' => 'text',     'label' => __( 'reCAPTCHA Site Key', 'insectra' ) ),
                'gtm_id'           => array( 'type' => 'text',     'label' => __( 'Google Tag Manager ID', 'insectra' ) ),
            ),
        );

        foreach ( $fields as $section => $items ) {
            foreach ( $items as $key => $cfg ) {
                add_settings_field( $key, $cfg['label'], array( $this, 'field' ), 'insectra-theme-options', $section,
                    array( 'key' => $key, 'cfg' => $cfg ) );
            }
        }
    }

    public function field( $args ) {
        $opts = get_option( self::OPTION, array() );
        $key  = $args['key']; $cfg = $args['cfg'];
        $val  = $opts[ $key ] ?? ( $cfg['default'] ?? '' );
        $name = self::OPTION . "[$key]";

        switch ( $cfg['type'] ) {
            case 'checkbox':
                printf( '<label><input type="checkbox" name="%s" value="1" %s> %s</label>',
                    esc_attr( $name ), checked( 1, $val, false ), esc_html__( 'Enable', 'insectra' ) );
                break;
            case 'select':
                echo '<select name="' . esc_attr( $name ) . '">';
                foreach ( $cfg['options'] as $k => $label ) {
                    printf( '<option value="%s" %s>%s</option>', esc_attr( $k ), selected( $k, $val, false ), esc_html( $label ) );
                }
                echo '</select>';
                break;
            case 'url':
            case 'text':
            default:
                printf( '<input type="%s" name="%s" value="%s" class="regular-text">',
                    esc_attr( $cfg['type'] ), esc_attr( $name ), esc_attr( $val ) );
        }
    }

    public function assets( $hook ) {
        if ( $hook !== 'toplevel_page_insectra-theme-options' ) return;
        // could enqueue color picker / media uploader here
    }

    public function render() {
        ?>
        <div class="wrap insectra-theme-options">
            <h1 style="display:flex;align-items:center;gap:14px">
                <span class="dashicons dashicons-admin-customizer" style="font-size:32px;color:#1FAE52"></span>
                <?php esc_html_e( 'Insectra Theme Options', 'insectra' ); ?>
            </h1>
            <p><?php esc_html_e( 'Configure global theme settings. Brand colors, hero, contact info, and per-section content live in', 'insectra' ); ?>
                <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Appearance → Customize', 'insectra' ); ?></a>.</p>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'insectra_theme_options_group' );
                do_settings_sections( 'insectra-theme-options' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public static function get( $key, $default = '' ) {
        $opts = get_option( self::OPTION, array() );
        return $opts[ $key ] ?? $default;
    }
}
new Insectra_Theme_Options();

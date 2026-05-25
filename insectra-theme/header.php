<?php
/**
 * Header template.
 *
 * @package Insectra
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'insectra' ); ?></a>

<?php
// Allow Elementor Pro Header location to override.
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'header' ) ) {
    // Header rendered by Elementor.
} else { ?>

<div class="ins-topbar">
    <div class="container">
        <ul class="topbar-info">
            <li><i class="fa-regular fa-clock"></i> <?php echo esc_html( insectra_tr( 'insectra_hours', __( 'Mon - Sat: 8:00 - 18:00', 'insectra' ) ) ); ?></li>
            <li><i class="fa-solid fa-location-dot"></i> <?php echo esc_html( insectra_tr( 'insectra_address', __( '123 Pest Control St., NY', 'insectra' ) ) ); ?></li>
            <li><i class="fa-regular fa-envelope"></i>
                <a href="mailto:<?php echo esc_attr( insectra_tr( 'insectra_email', 'info@insectra.com' ) ); ?>">
                    <?php echo esc_html( insectra_tr( 'insectra_email', 'info@insectra.com' ) ); ?>
                </a>
            </li>
        </ul>
        <ul class="topbar-extra">
            <?php insectra_lang_switcher(); ?>
            <li class="socials">
                <?php foreach ( insectra_get_socials() as $name => $url ) :
                    if ( ! $url ) continue; ?>
                    <a href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( $name ); ?>"><i class="fa-brands fa-<?php echo esc_attr( $name ); ?>"></i></a>
                <?php endforeach; ?>
            </li>
        </ul>
    </div>
</div>

<header id="masthead" class="ins-header">
    <div class="container header-inner">
        <div class="branding">
            <?php if ( has_custom_logo() ) {
                the_custom_logo();
            } else { ?>
                <a class="brand-text" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <span class="brand-icon"><i class="fa-solid fa-bug-slash"></i></span>
                    <span class="brand-name"><?php bloginfo( 'name' ); ?></span>
                </a>
            <?php } ?>
        </div>

        <nav class="ins-nav" aria-label="<?php esc_attr_e( 'Primary', 'insectra' ); ?>">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'menu',
                'fallback_cb'    => 'insectra_default_menu',
                'depth'          => 3,
                'walker'         => new Insectra_Walker_Nav(),
            ) );
            ?>
        </nav>

        <div class="header-actions">
            <a class="ins-phone" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', insectra_tr( 'insectra_phone', '+18005551234' ) ) ); ?>">
                <span class="phone-icon"><i class="fa-solid fa-phone"></i></span>
                <span class="phone-text">
                    <small><?php esc_html_e( 'Call us anytime', 'insectra' ); ?></small>
                    <strong><?php echo esc_html( insectra_tr( 'insectra_phone', '+1 (800) 555-1234' ) ); ?></strong>
                </span>
            </a>
            <a href="<?php echo esc_url( get_theme_mod( 'insectra_cta_url', '#contact' ) ); ?>" class="ins-btn ins-btn-primary">
                <?php echo esc_html( insectra_tr( 'insectra_cta_label', __( 'Get a Quote', 'insectra' ) ) ); ?>
                <i class="fa-solid fa-arrow-right arrow"></i>
            </a>
            <button class="ins-menu-toggle" aria-label="<?php esc_attr_e( 'Toggle menu', 'insectra' ); ?>"><span></span><span></span><span></span></button>
        </div>
    </div>
</header>

<div class="ins-mobile-menu" hidden>
    <?php wp_nav_menu( array( 'theme_location' => 'mobile', 'fallback_cb' => 'insectra_default_menu', 'menu_class' => 'mobile-menu' ) ); ?>
</div>

<?php } // end fallback header ?>

<main id="content" class="site-content">

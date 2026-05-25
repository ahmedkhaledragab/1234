<?php
/**
 * Footer template.
 *
 * @package Insectra
 */
?>
</main><!-- #content -->

<?php
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'footer' ) ) {
    // Footer rendered by Elementor Pro.
} else { ?>

<footer class="ins-footer">
    <div class="footer-cta">
        <div class="container">
            <div class="cta-card">
                <div>
                    <small><?php esc_html_e( 'Ready to start?', 'insectra' ); ?></small>
                    <h3><?php echo esc_html( insectra_tr( 'insectra_footer_cta', __( 'Need professional pest control? Book a free inspection today.', 'insectra' ) ) ); ?></h3>
                </div>
                <a href="<?php echo esc_url( get_theme_mod( 'insectra_cta_url', '#contact' ) ); ?>" class="ins-btn ins-btn-light">
                    <?php esc_html_e( 'Book Inspection', 'insectra' ); ?>
                    <i class="fa-solid fa-arrow-right arrow"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="footer-main">
        <div class="container footer-grid">
            <div class="col col-about">
                <?php if ( has_custom_logo() ) {
                    the_custom_logo();
                } else { ?>
                    <a class="brand-text light" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <span class="brand-icon"><i class="fa-solid fa-bug-slash"></i></span>
                        <span class="brand-name"><?php bloginfo( 'name' ); ?></span>
                    </a>
                <?php } ?>
                <p><?php echo esc_html( insectra_tr( 'insectra_footer_about', __( 'We deliver safe, eco-friendly pest control for homes and businesses with certified specialists.', 'insectra' ) ) ); ?></p>
                <ul class="col-social">
                    <?php foreach ( insectra_get_socials() as $name => $url ) :
                        if ( ! $url ) continue; ?>
                        <li><a href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( $name ); ?>"><i class="fa-brands fa-<?php echo esc_attr( $name ); ?>"></i></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col">
                <h5 class="widget-title"><?php esc_html_e( 'Quick Links', 'insectra' ); ?></h5>
                <?php if ( has_nav_menu( 'footer' ) ) {
                    wp_nav_menu( array( 'theme_location' => 'footer', 'menu_class' => 'footer-links', 'depth' => 1 ) );
                } else { ?>
                    <ul class="footer-links">
                        <li><a href="#"><?php esc_html_e( 'Home', 'insectra' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'About', 'insectra' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Services', 'insectra' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Contact', 'insectra' ); ?></a></li>
                    </ul>
                <?php } ?>
            </div>

            <div class="col">
                <h5 class="widget-title"><?php esc_html_e( 'Our Services', 'insectra' ); ?></h5>
                <ul class="footer-links">
                    <?php
                    $services = get_posts( array( 'post_type' => 'ins_service', 'posts_per_page' => 6 ) );
                    if ( $services ) {
                        foreach ( $services as $s ) {
                            echo '<li><a href="' . esc_url( get_permalink( $s ) ) . '">' . esc_html( get_the_title( $s ) ) . '</a></li>';
                        }
                    } else { ?>
                        <li><a href="#"><?php esc_html_e( 'Termite Control', 'insectra' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Rodent Control', 'insectra' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Mosquito Control', 'insectra' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Disinfection', 'insectra' ); ?></a></li>
                    <?php } ?>
                </ul>
            </div>

            <div class="col">
                <h5 class="widget-title"><?php esc_html_e( 'Contact Info', 'insectra' ); ?></h5>
                <ul class="footer-contact">
                    <li><i class="fa-solid fa-location-dot"></i> <?php echo esc_html( insectra_tr( 'insectra_address', __( '123 Pest Control St., NY', 'insectra' ) ) ); ?></li>
                    <li><i class="fa-solid fa-phone"></i> <a href="tel:<?php echo esc_attr( insectra_tr( 'insectra_phone', '+18005551234' ) ); ?>"><?php echo esc_html( insectra_tr( 'insectra_phone', '+1 (800) 555-1234' ) ); ?></a></li>
                    <li><i class="fa-regular fa-envelope"></i> <a href="mailto:<?php echo esc_attr( insectra_tr( 'insectra_email', 'info@insectra.com' ) ); ?>"><?php echo esc_html( insectra_tr( 'insectra_email', 'info@insectra.com' ) ); ?></a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <p>&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'insectra' ); ?></p>
            <p><?php esc_html_e( 'Made with', 'insectra' ); ?> <i class="fa-solid fa-heart" style="color:#1FAE52"></i> <?php esc_html_e( 'for a pest-free world.', 'insectra' ); ?></p>
        </div>
    </div>
</footer>

<?php } // end fallback footer ?>

<?php wp_footer(); ?>
</body>
</html>

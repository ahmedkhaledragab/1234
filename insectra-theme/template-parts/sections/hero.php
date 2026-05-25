<?php
/**
 * Hero section.
 *
 * @package Insectra
 */
$bg = get_theme_mod( 'insectra_hero_image', '' );
?>
<section class="ins-hero" <?php if ( $bg ) printf( 'style="background-image:linear-gradient(120deg, rgba(14,27,44,.85), rgba(14,27,44,.55)), url(%s)', esc_url( $bg ) ); ?>>
    <div class="container hero-grid">
        <div class="hero-copy">
            <span class="eyebrow"><i class="fa-solid fa-bug-slash"></i> <?php echo esc_html( insectra_tr( 'insectra_hero_eyebrow', __( 'Pest Control Experts', 'insectra' ) ) ); ?></span>
            <h1 class="hero-title"><?php echo esc_html( insectra_tr( 'insectra_hero_title', __( 'Safe & Reliable Pest Control Service', 'insectra' ) ) ); ?></h1>
            <p class="hero-subtitle"><?php echo esc_html( insectra_tr( 'insectra_hero_subtitle', __( 'We protect your home and business with eco-friendly treatments by certified specialists.', 'insectra' ) ) ); ?></p>
            <div class="hero-actions">
                <a class="ins-btn ins-btn-primary" href="<?php echo esc_url( get_theme_mod( 'insectra_cta_url', '#contact' ) ); ?>">
                    <?php esc_html_e( 'Get Free Quote', 'insectra' ); ?>
                    <i class="fa-solid fa-arrow-right arrow"></i>
                </a>
                <a class="ins-btn ins-btn-ghost" href="#services">
                    <?php esc_html_e( 'Our Services', 'insectra' ); ?>
                </a>
            </div>
            <ul class="hero-trust">
                <li><i class="fa-solid fa-shield-halved"></i> <?php esc_html_e( 'Certified Specialists', 'insectra' ); ?></li>
                <li><i class="fa-solid fa-leaf"></i> <?php esc_html_e( 'Eco-friendly Methods', 'insectra' ); ?></li>
                <li><i class="fa-solid fa-clock"></i> <?php esc_html_e( '24/7 Support', 'insectra' ); ?></li>
            </ul>
        </div>
        <div class="hero-visual" aria-hidden="true">
            <div class="bug-circle">
                <i class="fa-solid fa-bug"></i>
            </div>
            <div class="floating f1"><i class="fa-solid fa-spider"></i></div>
            <div class="floating f2"><i class="fa-solid fa-mosquito"></i></div>
            <div class="floating f3"><i class="fa-solid fa-locust"></i></div>
        </div>
    </div>
    <div class="hero-shape-left" aria-hidden="true"></div>
</section>

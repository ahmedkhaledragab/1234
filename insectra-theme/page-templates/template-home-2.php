<?php
/**
 * Template Name: Home Layout 2 (Dark Hero)
 *
 * Alternative homepage layout with reordered sections and accent variations.
 *
 * @package Insectra
 */
get_header(); ?>

<?php insectra_elementor_first( function() { ?>

<?php
// Hero v2 - dark with overlay
$bg = get_theme_mod( 'insectra_hero_image', '' );
?>
<section class="ins-hero ins-hero-v2" <?php if ( $bg ) printf( 'style="background-image:linear-gradient(135deg, rgba(14,27,44,.92), rgba(15,138,62,.6)), url(%s)', esc_url( $bg ) ); ?>>
    <div class="container hero-grid">
        <div class="hero-copy text-center" style="grid-column:1/-1;max-width:860px;margin:0 auto">
            <span class="eyebrow"><i class="fa-solid fa-bug-slash"></i> <?php echo esc_html( insectra_tr( 'insectra_hero_eyebrow', __( 'Pest Control Experts', 'insectra' ) ) ); ?></span>
            <h1 class="hero-title"><?php echo esc_html( insectra_tr( 'insectra_hero_title', __( 'Safe & Reliable Pest Control Service', 'insectra' ) ) ); ?></h1>
            <p class="hero-subtitle" style="margin-left:auto;margin-right:auto"><?php echo esc_html( insectra_tr( 'insectra_hero_subtitle', __( 'We protect your home and business with eco-friendly treatments by certified specialists.', 'insectra' ) ) ); ?></p>
            <div class="hero-actions" style="justify-content:center">
                <a class="ins-btn ins-btn-primary" href="<?php echo esc_url( get_theme_mod( 'insectra_cta_url', '#contact' ) ); ?>">
                    <?php esc_html_e( 'Get Free Quote', 'insectra' ); ?> <i class="fa-solid fa-arrow-right arrow"></i>
                </a>
                <a class="ins-btn ins-btn-ghost" href="#services"><?php esc_html_e( 'Our Services', 'insectra' ); ?></a>
            </div>
        </div>
    </div>
</section>

<?php get_template_part( 'template-parts/sections/services' ); ?>
<?php get_template_part( 'template-parts/sections/about' ); ?>
<?php get_template_part( 'template-parts/sections/counter' ); ?>
<?php get_template_part( 'template-parts/sections/projects' ); ?>
<?php get_template_part( 'template-parts/sections/team' ); ?>
<?php get_template_part( 'template-parts/sections/pricing' ); ?>
<?php get_template_part( 'template-parts/sections/testimonials' ); ?>
<?php get_template_part( 'template-parts/sections/blog' ); ?>
<?php get_template_part( 'template-parts/sections/contact' ); ?>

<?php } ); ?>

<?php get_footer();

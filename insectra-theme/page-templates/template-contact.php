<?php
/**
 * Template Name: Contact Page
 *
 * @package Insectra
 */
get_header(); ?>

<section class="ins-page-banner">
    <div class="container">
        <h1><?php the_title(); ?></h1>
        <nav class="breadcrumbs"><?php insectra_breadcrumbs(); ?></nav>
    </div>
</section>

<?php insectra_elementor_first( function() { ?>

<section class="ins-contact-info-cards ins-section" style="padding-bottom:0">
    <div class="container info-cards-grid">
        <div class="info-card">
            <span class="ic"><i class="fa-solid fa-location-dot"></i></span>
            <h4><?php esc_html_e( 'Visit Office', 'insectra' ); ?></h4>
            <p><?php echo esc_html( insectra_tr( 'insectra_address', '123 Pest Control St., NY' ) ); ?></p>
        </div>
        <div class="info-card">
            <span class="ic"><i class="fa-solid fa-phone"></i></span>
            <h4><?php esc_html_e( 'Call Anytime', 'insectra' ); ?></h4>
            <p><a href="tel:<?php echo esc_attr( insectra_tr( 'insectra_phone', '' ) ); ?>"><?php echo esc_html( insectra_tr( 'insectra_phone', '+1 (800) 555-1234' ) ); ?></a></p>
        </div>
        <div class="info-card">
            <span class="ic"><i class="fa-regular fa-envelope"></i></span>
            <h4><?php esc_html_e( 'Email Us', 'insectra' ); ?></h4>
            <p><a href="mailto:<?php echo esc_attr( insectra_tr( 'insectra_email', '' ) ); ?>"><?php echo esc_html( insectra_tr( 'insectra_email', 'info@insectra.com' ) ); ?></a></p>
        </div>
        <div class="info-card">
            <span class="ic"><i class="fa-regular fa-clock"></i></span>
            <h4><?php esc_html_e( 'Working Hours', 'insectra' ); ?></h4>
            <p><?php echo esc_html( insectra_tr( 'insectra_hours', __( 'Mon - Sat: 8:00 - 18:00', 'insectra' ) ) ); ?></p>
        </div>
    </div>
</section>

<?php get_template_part( 'template-parts/sections/contact' ); ?>

<section class="ins-map">
    <?php
    $map_embed = get_theme_mod( 'insectra_map_embed', '' );
    if ( $map_embed ) {
        echo wp_kses_post( $map_embed );
    } else { ?>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1422937950147!2d-74.0059418!3d40.7127753!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDDCsDQyJzQ2LjAiTiA3NMKwMDAnMjEuNCJX!5e0!3m2!1sen!2sus!4v1716000000000" width="100%" height="450" style="border:0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    <?php } ?>
</section>

<?php
while ( have_posts() ) : the_post();
    if ( get_the_content() ) : ?>
        <section class="ins-section">
            <div class="container">
                <article class="ins-page-content"><?php the_content(); ?></article>
            </div>
        </section>
    <?php endif;
endwhile; ?>

<?php } ); // end elementor_first ?>

<?php get_footer();

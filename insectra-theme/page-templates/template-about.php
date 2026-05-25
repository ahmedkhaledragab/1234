<?php
/**
 * Template Name: About Page
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

<?php get_template_part( 'template-parts/sections/about' ); ?>
<?php get_template_part( 'template-parts/sections/counter' ); ?>
<?php get_template_part( 'template-parts/sections/team' ); ?>
<?php get_template_part( 'template-parts/sections/testimonials' ); ?>

<section class="ins-section ins-mission">
    <div class="container mission-grid">
        <div class="mission-item">
            <span class="ic"><i class="fa-solid fa-bullseye"></i></span>
            <h3><?php esc_html_e( 'Our Mission', 'insectra' ); ?></h3>
            <p><?php esc_html_e( 'To deliver safe, effective and eco-friendly pest control that protects families, employees and the environment.', 'insectra' ); ?></p>
        </div>
        <div class="mission-item">
            <span class="ic"><i class="fa-solid fa-eye"></i></span>
            <h3><?php esc_html_e( 'Our Vision', 'insectra' ); ?></h3>
            <p><?php esc_html_e( 'To be the most trusted pest control company in the region, known for quality, reliability and innovation.', 'insectra' ); ?></p>
        </div>
        <div class="mission-item">
            <span class="ic"><i class="fa-solid fa-handshake"></i></span>
            <h3><?php esc_html_e( 'Our Values', 'insectra' ); ?></h3>
            <p><?php esc_html_e( 'Integrity, customer focus, environmental responsibility, and continuous improvement guide everything we do.', 'insectra' ); ?></p>
        </div>
    </div>
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

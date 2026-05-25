<?php
/**
 * Front page (home).
 *
 * If a static page is set as Front Page and uses Elementor, that page's
 * Elementor content is rendered here. Otherwise, prebuilt sections are shown.
 *
 * @package Insectra
 */
get_header();

// Elementor-first: render Elementor content if the static front-page is built with it.
if ( is_page() && insectra_is_built_with_elementor() ) {
    while ( have_posts() ) { the_post(); the_content(); }
    get_footer();
    return;
}
?>

<?php get_template_part( 'template-parts/sections/hero' ); ?>
<?php get_template_part( 'template-parts/sections/features' ); ?>
<?php get_template_part( 'template-parts/sections/about' ); ?>
<?php get_template_part( 'template-parts/sections/services' ); ?>
<?php get_template_part( 'template-parts/sections/counter' ); ?>
<?php get_template_part( 'template-parts/sections/pricing' ); ?>
<?php get_template_part( 'template-parts/sections/team' ); ?>
<?php get_template_part( 'template-parts/sections/projects' ); ?>
<?php get_template_part( 'template-parts/sections/testimonials' ); ?>
<?php get_template_part( 'template-parts/sections/brands' ); ?>
<?php get_template_part( 'template-parts/sections/blog' ); ?>
<?php get_template_part( 'template-parts/sections/contact' ); ?>

<?php get_footer();

<?php
/**
 * Front page (home).
 *
 * Note: If a static page is set as front page and uses Elementor, that page's
 * Elementor content will load via page.php. This file is the fallback hero
 * homepage with predefined Insectra sections you can reorder via Customizer.
 *
 * @package Insectra
 */
get_header();

// If page assigned as front and has Elementor edits, defer to page.php content.
if ( is_page() && class_exists( '\Elementor\Plugin' ) ) {
    $document = \Elementor\Plugin::$instance->documents->get( get_the_ID() );
    if ( $document && $document->is_built_with_elementor() ) {
        while ( have_posts() ) { the_post(); the_content(); }
        get_footer(); return;
    }
}
?>

<?php get_template_part( 'template-parts/sections/hero' ); ?>
<?php get_template_part( 'template-parts/sections/features' ); ?>
<?php get_template_part( 'template-parts/sections/about' ); ?>
<?php get_template_part( 'template-parts/sections/services' ); ?>
<?php get_template_part( 'template-parts/sections/counter' ); ?>
<?php get_template_part( 'template-parts/sections/pricing' ); ?>
<?php get_template_part( 'template-parts/sections/team' ); ?>
<?php get_template_part( 'template-parts/sections/testimonials' ); ?>
<?php get_template_part( 'template-parts/sections/blog' ); ?>
<?php get_template_part( 'template-parts/sections/contact' ); ?>

<?php get_footer();

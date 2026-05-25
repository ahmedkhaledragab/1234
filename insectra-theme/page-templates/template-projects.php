<?php
/**
 * Template Name: Projects Page
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

<?php get_template_part( 'template-parts/sections/projects' ); ?>
<?php get_template_part( 'template-parts/sections/counter' ); ?>
<?php get_template_part( 'template-parts/sections/testimonials' ); ?>

<?php } ); ?>

<?php get_footer();

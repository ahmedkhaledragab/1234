<?php
/**
 * Template Name: Services Page
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

<?php get_template_part( 'template-parts/sections/services' ); ?>
<?php get_template_part( 'template-parts/sections/features' ); ?>
<?php get_template_part( 'template-parts/sections/pricing' ); ?>
<?php get_template_part( 'template-parts/sections/contact' ); ?>

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

<?php } ); ?>

<?php get_footer();

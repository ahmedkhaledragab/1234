<?php
/**
 * Single page.
 *
 * @package Insectra
 */
get_header(); ?>

<?php while ( have_posts() ) : the_post();
    // If Elementor canvas / full-width, skip banner.
    $tpl = get_page_template_slug();
    if ( $tpl !== 'elementor_canvas' && $tpl !== 'elementor_header_footer' ) : ?>
    <section class="ins-page-banner">
        <div class="container">
            <h1><?php the_title(); ?></h1>
            <nav class="breadcrumbs"><?php insectra_breadcrumbs(); ?></nav>
        </div>
    </section>
    <?php endif; ?>

    <section class="ins-section">
        <div class="container">
            <article <?php post_class( 'ins-page-content' ); ?>>
                <?php the_content(); ?>
                <?php wp_link_pages(); ?>
            </article>
            <?php if ( comments_open() || get_comments_number() ) comments_template(); ?>
        </div>
    </section>
<?php endwhile; ?>

<?php get_footer(); ?>

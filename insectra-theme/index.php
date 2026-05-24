<?php
/**
 * Main template (blog index, fallback).
 *
 * @package Insectra
 */
get_header(); ?>

<section class="ins-page-banner">
    <div class="container">
        <h1><?php
            if ( is_home() && ! is_front_page() ) { single_post_title(); }
            elseif ( is_archive() ) { the_archive_title(); }
            elseif ( is_search() ) { printf( esc_html__( 'Search results for: %s', 'insectra' ), '<span>' . get_search_query() . '</span>' ); }
            else { esc_html_e( 'Latest News', 'insectra' ); }
        ?></h1>
        <nav class="breadcrumbs"><?php insectra_breadcrumbs(); ?></nav>
    </div>
</section>

<section class="ins-section">
    <div class="container blog-layout">
        <div class="blog-main">
            <?php if ( have_posts() ) : ?>
                <div class="ins-blog-grid">
                    <?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/content', 'card' ); endwhile; ?>
                </div>
                <div class="ins-pagination"><?php the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => '<i class="fa-solid fa-arrow-left"></i>', 'next_text' => '<i class="fa-solid fa-arrow-right"></i>' ) ); ?></div>
            <?php else : ?>
                <p><?php esc_html_e( 'No posts found.', 'insectra' ); ?></p>
            <?php endif; ?>
        </div>
        <aside class="blog-sidebar"><?php if ( is_active_sidebar( 'blog-sidebar' ) ) dynamic_sidebar( 'blog-sidebar' ); ?></aside>
    </div>
</section>

<?php get_footer(); ?>

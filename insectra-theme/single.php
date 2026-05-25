<?php
/**
 * Single post.
 *
 * @package Insectra
 */
get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
    <section class="ins-page-banner">
        <div class="container">
            <h1><?php the_title(); ?></h1>
            <nav class="breadcrumbs"><?php insectra_breadcrumbs(); ?></nav>
        </div>
    </section>

    <section class="ins-section">
        <div class="container blog-layout">
            <article <?php post_class( 'ins-single-post blog-main' ); ?>>
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="post-thumb"><?php the_post_thumbnail( 'large' ); ?></div>
                <?php endif; ?>
                <div class="post-meta">
                    <span><i class="fa-regular fa-user"></i> <?php the_author(); ?></span>
                    <span><i class="fa-regular fa-calendar"></i> <?php echo esc_html( get_the_date() ); ?></span>
                    <span><i class="fa-regular fa-folder"></i> <?php the_category( ', ' ); ?></span>
                </div>
                <div class="post-content"><?php the_content(); ?></div>
                <?php wp_link_pages(); ?>
                <?php if ( comments_open() || get_comments_number() ) comments_template(); ?>
            </article>
            <aside class="blog-sidebar"><?php if ( is_active_sidebar( 'blog-sidebar' ) ) dynamic_sidebar( 'blog-sidebar' ); ?></aside>
        </div>
    </section>
<?php endwhile; ?>

<?php get_footer(); ?>

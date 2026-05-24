<?php
/**
 * Blog section (latest posts).
 *
 * @package Insectra
 */
$q = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 3, 'ignore_sticky_posts' => 1 ) );
if ( ! $q->have_posts() ) return;
?>
<section class="ins-section ins-blog" id="blog">
    <div class="container">
        <div class="section-head text-center">
            <span class="eyebrow"><?php esc_html_e( 'Latest News', 'insectra' ); ?></span>
            <h2 class="section-title"><?php esc_html_e( 'Pest control tips & news', 'insectra' ); ?></h2>
        </div>
        <div class="ins-blog-grid">
            <?php while ( $q->have_posts() ) : $q->the_post(); get_template_part( 'template-parts/content', 'card' ); endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>

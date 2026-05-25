<?php
/**
 * Generic WooCommerce wrapper - all WooCommerce templates render through here.
 *
 * @package Insectra
 */
get_header(); ?>

<section class="ins-page-banner">
    <div class="container">
        <h1><?php
            if ( function_exists( 'is_shop' ) && is_shop() ) {
                echo esc_html( woocommerce_page_title( false ) );
            } elseif ( is_product_category() ) {
                single_term_title();
            } elseif ( is_product() ) {
                the_title();
            } else {
                woocommerce_page_title();
            }
        ?></h1>
        <nav class="breadcrumbs"><?php insectra_breadcrumbs(); ?></nav>
    </div>
</section>

<section class="ins-section ins-woocommerce">
    <div class="container">
        <?php woocommerce_content(); ?>
    </div>
</section>

<?php get_footer();

<?php
/**
 * 404 template.
 *
 * @package Insectra
 */
get_header(); ?>

<section class="ins-section ins-404">
    <div class="container text-center">
        <div class="error-code">404</div>
        <h1><?php esc_html_e( 'Page not found', 'insectra' ); ?></h1>
        <p><?php esc_html_e( 'The page you are looking for does not exist or has been moved.', 'insectra' ); ?></p>
        <a class="ins-btn ins-btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
            <?php esc_html_e( 'Back to Home', 'insectra' ); ?>
            <i class="fa-solid fa-arrow-right arrow"></i>
        </a>
    </div>
</section>

<?php get_footer(); ?>

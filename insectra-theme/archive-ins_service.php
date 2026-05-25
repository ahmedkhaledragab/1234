<?php
/**
 * Services archive.
 *
 * @package Insectra
 */
get_header(); ?>

<section class="ins-page-banner">
    <div class="container">
        <h1><?php post_type_archive_title(); ?></h1>
        <nav class="breadcrumbs"><?php insectra_breadcrumbs(); ?></nav>
    </div>
</section>

<section class="ins-section ins-services">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="services-grid">
                <?php while ( have_posts() ) : the_post();
                    $icon  = get_post_meta( get_the_ID(), '_ins_icon', true ) ?: 'fa-solid fa-shield';
                    $short = get_post_meta( get_the_ID(), '_ins_short', true ) ?: get_the_excerpt();
                    ?>
                    <article class="ins-service-card">
                        <span class="corner"></span>
                        <span class="card-icon"><i class="<?php echo esc_attr( $icon ); ?>"></i></span>
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p><?php echo esc_html( $short ); ?></p>
                        <a class="read-more" href="<?php the_permalink(); ?>">
                            <?php esc_html_e( 'Read More', 'insectra' ); ?> <i class="fa-solid fa-arrow-right arrow"></i>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>
            <div class="ins-pagination"><?php the_posts_pagination(); ?></div>
        <?php else : ?>
            <p><?php esc_html_e( 'No services yet. Add some from the dashboard.', 'insectra' ); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer();

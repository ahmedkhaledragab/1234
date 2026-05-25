<?php
/**
 * Single Project.
 *
 * @package Insectra
 */
get_header(); ?>

<?php while ( have_posts() ) : the_post();
    $client   = get_post_meta( get_the_ID(), '_ins_client', true );
    $location = get_post_meta( get_the_ID(), '_ins_location', true );
    $date     = get_post_meta( get_the_ID(), '_ins_date', true );
    $duration = get_post_meta( get_the_ID(), '_ins_duration', true );
    $url      = get_post_meta( get_the_ID(), '_ins_url', true );
    ?>
    <section class="ins-page-banner">
        <div class="container">
            <h1><?php the_title(); ?></h1>
            <nav class="breadcrumbs"><?php insectra_breadcrumbs(); ?></nav>
        </div>
    </section>

    <?php if ( insectra_is_built_with_elementor() ) {
        the_content();
    } else { ?>
    <section class="ins-section ins-single-project">
        <div class="container">
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="project-hero"><?php the_post_thumbnail( 'large' ); ?></div>
            <?php endif; ?>

            <div class="project-meta-grid">
                <?php if ( $client )   : ?><div><span><?php esc_html_e( 'Client',   'insectra' ); ?></span><strong><?php echo esc_html( $client ); ?></strong></div><?php endif; ?>
                <?php if ( $location ) : ?><div><span><?php esc_html_e( 'Location', 'insectra' ); ?></span><strong><?php echo esc_html( $location ); ?></strong></div><?php endif; ?>
                <?php if ( $date )     : ?><div><span><?php esc_html_e( 'Date',     'insectra' ); ?></span><strong><?php echo esc_html( $date ); ?></strong></div><?php endif; ?>
                <?php if ( $duration ) : ?><div><span><?php esc_html_e( 'Duration', 'insectra' ); ?></span><strong><?php echo esc_html( $duration ); ?></strong></div><?php endif; ?>
                <?php if ( $url )      : ?><div><span><?php esc_html_e( 'Website',  'insectra' ); ?></span><a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( wp_parse_url( $url, PHP_URL_HOST ) ); ?></a></div><?php endif; ?>
            </div>

            <div class="post-content"><?php the_content(); ?></div>
        </div>
    </section>
    <?php } ?>
<?php endwhile; ?>

<?php get_footer();

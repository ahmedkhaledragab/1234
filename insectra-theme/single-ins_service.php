<?php
/**
 * Single Service.
 *
 * @package Insectra
 */
get_header(); ?>

<?php while ( have_posts() ) : the_post();
    $icon       = get_post_meta( get_the_ID(), '_ins_icon', true ) ?: 'fa-solid fa-shield';
    $price_from = get_post_meta( get_the_ID(), '_ins_price_from', true );
    $duration   = get_post_meta( get_the_ID(), '_ins_duration', true );
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
    <section class="ins-section ins-single-service">
        <div class="container service-layout">
            <article class="service-main">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="post-thumb"><?php the_post_thumbnail( 'large' ); ?></div>
                <?php endif; ?>
                <div class="service-icon-row">
                    <span class="big-icon"><i class="<?php echo esc_attr( $icon ); ?>"></i></span>
                    <h2><?php the_title(); ?></h2>
                </div>
                <div class="post-content"><?php the_content(); ?></div>
            </article>

            <aside class="service-sidebar">
                <div class="widget">
                    <h4 class="widget-title"><?php esc_html_e( 'Service Info', 'insectra' ); ?></h4>
                    <ul class="info-list">
                        <?php if ( $price_from ) : ?>
                            <li><span class="ic"><i class="fa-solid fa-tag"></i></span><div><strong><?php esc_html_e( 'Starting From', 'insectra' ); ?></strong><span><?php echo esc_html( $price_from ); ?></span></div></li>
                        <?php endif; ?>
                        <?php if ( $duration ) : ?>
                            <li><span class="ic"><i class="fa-regular fa-clock"></i></span><div><strong><?php esc_html_e( 'Duration', 'insectra' ); ?></strong><span><?php echo esc_html( $duration ); ?></span></div></li>
                        <?php endif; ?>
                        <li><span class="ic"><i class="fa-solid fa-shield-halved"></i></span><div><strong><?php esc_html_e( 'Guarantee', 'insectra' ); ?></strong><span><?php esc_html_e( '90-day satisfaction', 'insectra' ); ?></span></div></li>
                    </ul>
                    <a href="#contact" class="ins-btn ins-btn-primary" style="width:100%;justify-content:center;margin-top:18px"><?php esc_html_e( 'Book This Service', 'insectra' ); ?> <i class="fa-solid fa-arrow-right arrow"></i></a>
                </div>

                <div class="widget">
                    <h4 class="widget-title"><?php esc_html_e( 'Other Services', 'insectra' ); ?></h4>
                    <ul class="footer-links">
                        <?php
                        $others = get_posts( array( 'post_type' => 'ins_service', 'posts_per_page' => 5, 'exclude' => array( get_the_ID() ) ) );
                        foreach ( $others as $o ) {
                            echo '<li><a href="' . esc_url( get_permalink( $o ) ) . '">' . esc_html( get_the_title( $o ) ) . ' <i class="fa-solid fa-arrow-right" style="float:right;font-size:11px;margin-top:6px"></i></a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </aside>
        </div>
    </section>
    <?php } ?>
<?php endwhile; ?>

<?php get_template_part( 'template-parts/sections/contact' ); ?>

<?php get_footer();

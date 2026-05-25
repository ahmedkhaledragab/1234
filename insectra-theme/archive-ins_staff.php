<?php
/**
 * Staff archive.
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

<section class="ins-section ins-team">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="team-grid">
                <?php while ( have_posts() ) : the_post();
                    $role = get_post_meta( get_the_ID(), '_ins_role', true );
                    $fb   = get_post_meta( get_the_ID(), '_ins_facebook', true );
                    $tw   = get_post_meta( get_the_ID(), '_ins_twitter', true );
                    $li   = get_post_meta( get_the_ID(), '_ins_linkedin', true );
                    ?>
                    <article class="ins-team-card">
                        <div class="photo<?php echo has_post_thumbnail() ? '' : ' no-image'; ?>">
                            <?php if ( has_post_thumbnail() ) the_post_thumbnail( 'insectra-team' ); else echo '<i class="fa-solid fa-user"></i>'; ?>
                            <div class="socials">
                                <?php if ( $fb ) echo '<a href="' . esc_url( $fb ) . '"><i class="fa-brands fa-facebook"></i></a>'; ?>
                                <?php if ( $tw ) echo '<a href="' . esc_url( $tw ) . '"><i class="fa-brands fa-twitter"></i></a>'; ?>
                                <?php if ( $li ) echo '<a href="' . esc_url( $li ) . '"><i class="fa-brands fa-linkedin"></i></a>'; ?>
                            </div>
                        </div>
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p class="role"><?php echo esc_html( $role ); ?></p>
                    </article>
                <?php endwhile; ?>
            </div>
            <div class="ins-pagination"><?php the_posts_pagination(); ?></div>
        <?php else : ?>
            <p><?php esc_html_e( 'No staff members yet.', 'insectra' ); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer();

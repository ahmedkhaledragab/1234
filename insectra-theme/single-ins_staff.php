<?php
/**
 * Single Staff.
 *
 * @package Insectra
 */
get_header(); ?>

<?php while ( have_posts() ) : the_post();
    $role  = get_post_meta( get_the_ID(), '_ins_role', true );
    $phone = get_post_meta( get_the_ID(), '_ins_phone', true );
    $email = get_post_meta( get_the_ID(), '_ins_email', true );
    $fb    = get_post_meta( get_the_ID(), '_ins_facebook', true );
    $tw    = get_post_meta( get_the_ID(), '_ins_twitter', true );
    $li    = get_post_meta( get_the_ID(), '_ins_linkedin', true );
    $ig    = get_post_meta( get_the_ID(), '_ins_instagram', true );
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
    <section class="ins-section ins-single-staff">
        <div class="container staff-layout">
            <div class="staff-photo">
                <?php if ( has_post_thumbnail() ) the_post_thumbnail( 'large' ); else echo '<div class="no-image"><i class="fa-solid fa-user"></i></div>'; ?>
                <div class="socials">
                    <?php if ( $fb ) echo '<a href="' . esc_url( $fb ) . '"><i class="fa-brands fa-facebook"></i></a>'; ?>
                    <?php if ( $tw ) echo '<a href="' . esc_url( $tw ) . '"><i class="fa-brands fa-twitter"></i></a>'; ?>
                    <?php if ( $li ) echo '<a href="' . esc_url( $li ) . '"><i class="fa-brands fa-linkedin"></i></a>'; ?>
                    <?php if ( $ig ) echo '<a href="' . esc_url( $ig ) . '"><i class="fa-brands fa-instagram"></i></a>'; ?>
                </div>
            </div>
            <div class="staff-bio">
                <h2><?php the_title(); ?></h2>
                <p class="role"><?php echo esc_html( $role ); ?></p>
                <ul class="info-list">
                    <?php if ( $phone ) : ?><li><span class="ic"><i class="fa-solid fa-phone"></i></span><div><strong><?php esc_html_e( 'Phone', 'insectra' ); ?></strong><a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a></div></li><?php endif; ?>
                    <?php if ( $email ) : ?><li><span class="ic"><i class="fa-regular fa-envelope"></i></span><div><strong><?php esc_html_e( 'Email', 'insectra' ); ?></strong><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></div></li><?php endif; ?>
                </ul>
                <div class="post-content"><?php the_content(); ?></div>
            </div>
        </div>
    </section>
    <?php } ?>
<?php endwhile; ?>

<?php get_footer();

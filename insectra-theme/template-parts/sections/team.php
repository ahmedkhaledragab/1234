<?php
/**
 * Staff (team) section.
 *
 * @package Insectra
 */
$members = get_posts( array( 'post_type' => 'ins_staff', 'posts_per_page' => 4 ) );
?>
<section class="ins-section ins-team" id="team">
    <div class="container">
        <div class="section-head text-center">
            <span class="eyebrow"><?php esc_html_e( 'Our Specialists', 'insectra' ); ?></span>
            <h2 class="section-title"><?php esc_html_e( 'Meet our certified pest control team', 'insectra' ); ?></h2>
        </div>
        <div class="team-grid">
            <?php if ( $members ) :
                foreach ( $members as $m ) :
                    $role = get_post_meta( $m->ID, '_ins_role', true );
                    $fb   = get_post_meta( $m->ID, '_ins_facebook', true );
                    $tw   = get_post_meta( $m->ID, '_ins_twitter', true );
                    $li   = get_post_meta( $m->ID, '_ins_linkedin', true );
                    $ig   = get_post_meta( $m->ID, '_ins_instagram', true );
                    ?>
                    <article class="ins-team-card">
                        <div class="photo<?php echo has_post_thumbnail( $m ) ? '' : ' no-image'; ?>">
                            <?php if ( has_post_thumbnail( $m ) ) {
                                echo get_the_post_thumbnail( $m, 'insectra-team' );
                            } else { echo '<i class="fa-solid fa-user"></i>'; } ?>
                            <div class="socials">
                                <?php if ( $fb ) echo '<a href="' . esc_url( $fb ) . '"><i class="fa-brands fa-facebook"></i></a>'; ?>
                                <?php if ( $tw ) echo '<a href="' . esc_url( $tw ) . '"><i class="fa-brands fa-twitter"></i></a>'; ?>
                                <?php if ( $li ) echo '<a href="' . esc_url( $li ) . '"><i class="fa-brands fa-linkedin"></i></a>'; ?>
                                <?php if ( $ig ) echo '<a href="' . esc_url( $ig ) . '"><i class="fa-brands fa-instagram"></i></a>'; ?>
                            </div>
                        </div>
                        <h3><a href="<?php echo esc_url( get_permalink( $m ) ); ?>"><?php echo esc_html( $m->post_title ); ?></a></h3>
                        <p class="role"><?php echo esc_html( $role ); ?></p>
                    </article>
                <?php endforeach;
            else :
                $demo = array(
                    array( 'name' => 'John Carter',    'role' => __( 'Senior Specialist', 'insectra' ) ),
                    array( 'name' => 'Sarah Williams', 'role' => __( 'Lead Inspector',    'insectra' ) ),
                    array( 'name' => 'Michael Brown',  'role' => __( 'Field Technician',  'insectra' ) ),
                    array( 'name' => 'Linda Davis',    'role' => __( 'Disinfection Lead', 'insectra' ) ),
                );
                foreach ( $demo as $d ) : ?>
                    <article class="ins-team-card">
                        <div class="photo no-image"><i class="fa-solid fa-user"></i>
                            <div class="socials">
                                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                <a href="#"><i class="fa-brands fa-linkedin"></i></a>
                            </div>
                        </div>
                        <h3><?php echo esc_html( $d['name'] ); ?></h3>
                        <p class="role"><?php echo esc_html( $d['role'] ); ?></p>
                    </article>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
</section>

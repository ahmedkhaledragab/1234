<?php
/**
 * Testimonials.
 *
 * @package Insectra
 */
$items = get_posts( array( 'post_type' => 'ins_testimonial', 'posts_per_page' => 6 ) );

$fallback = array(
    array( 'name' => 'Emily R.',  'role' => __( 'Homeowner', 'insectra' ),     'rating' => 5, 'text' => __( 'Insectra solved our termite problem in one visit. Professional and friendly!', 'insectra' ) ),
    array( 'name' => 'David P.',  'role' => __( 'Restaurant Manager', 'insectra' ), 'rating' => 5, 'text' => __( 'Our restaurant has been pest-free for over a year thanks to their monthly plan.', 'insectra' ) ),
    array( 'name' => 'Layla M.',  'role' => __( 'Office Manager', 'insectra' ),'rating' => 4, 'text' => __( 'Great team, fair pricing, and eco-friendly. Highly recommended.', 'insectra' ) ),
);
?>
<section class="ins-section ins-testimonials" id="testimonials">
    <div class="container">
        <div class="section-head text-center">
            <span class="eyebrow"><?php esc_html_e( 'Testimonials', 'insectra' ); ?></span>
            <h2 class="section-title"><?php esc_html_e( 'What our clients say', 'insectra' ); ?></h2>
        </div>
        <div class="testi-grid">
            <?php if ( $items ) :
                foreach ( $items as $t ) :
                    $role = get_post_meta( $t->ID, '_ins_role', true );
                    $rating = (int) get_post_meta( $t->ID, '_ins_rating', true ) ?: 5;
                    ?>
                    <article class="ins-testi-card">
                        <i class="quote fa-solid fa-quote-right"></i>
                        <?php echo insectra_stars( $rating ); ?>
                        <p><?php echo esc_html( wp_strip_all_tags( $t->post_content ) ); ?></p>
                        <div class="author">
                            <?php if ( has_post_thumbnail( $t ) ) echo get_the_post_thumbnail( $t, array( 60, 60 ), array( 'class' => 'avatar' ) ); ?>
                            <div>
                                <strong><?php echo esc_html( $t->post_title ); ?></strong>
                                <span><?php echo esc_html( $role ); ?></span>
                            </div>
                        </div>
                    </article>
                <?php endforeach;
            else :
                foreach ( $fallback as $t ) : ?>
                    <article class="ins-testi-card">
                        <i class="quote fa-solid fa-quote-right"></i>
                        <?php echo insectra_stars( $t['rating'] ); ?>
                        <p>"<?php echo esc_html( $t['text'] ); ?>"</p>
                        <div class="author">
                            <div class="avatar avatar-fallback"><i class="fa-solid fa-user"></i></div>
                            <div>
                                <strong><?php echo esc_html( $t['name'] ); ?></strong>
                                <span><?php echo esc_html( $t['role'] ); ?></span>
                            </div>
                        </div>
                    </article>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
</section>

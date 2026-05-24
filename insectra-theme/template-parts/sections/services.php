<?php
/**
 * Services section.
 *
 * @package Insectra
 */
$services = get_posts( array( 'post_type' => 'ins_service', 'posts_per_page' => 6 ) );

$fallback = array(
    array( 'icon' => 'fa-solid fa-bug',           'title' => __( 'Termite Control',  'insectra' ), 'desc' => __( 'Complete termite removal and long-term prevention.', 'insectra' ) ),
    array( 'icon' => 'fa-solid fa-mosquito',      'title' => __( 'Mosquito Control', 'insectra' ), 'desc' => __( 'Outdoor and indoor treatments for mosquito-free spaces.', 'insectra' ) ),
    array( 'icon' => 'fa-solid fa-spider',        'title' => __( 'Spider Control',   'insectra' ), 'desc' => __( 'Professional spider removal and web cleaning service.', 'insectra' ) ),
    array( 'icon' => 'fa-solid fa-shield-virus',  'title' => __( 'Disinfection',     'insectra' ), 'desc' => __( 'Hospital-grade disinfection for homes and offices.', 'insectra' ) ),
    array( 'icon' => 'fa-solid fa-locust',        'title' => __( 'Cockroach Control','insectra' ), 'desc' => __( 'Get rid of roaches with safe, gel-based treatments.',  'insectra' ) ),
    array( 'icon' => 'fa-solid fa-paw',           'title' => __( 'Rodent Control',   'insectra' ), 'desc' => __( 'Humane and effective rodent removal solutions.',     'insectra' ) ),
);
?>
<section class="ins-section ins-services" id="services">
    <div class="container">
        <div class="section-head text-center">
            <span class="eyebrow"><?php esc_html_e( 'What We Offer', 'insectra' ); ?></span>
            <h2 class="section-title"><?php esc_html_e( 'Pest control services we provide', 'insectra' ); ?></h2>
            <p class="section-sub"><?php esc_html_e( 'A complete range of professional pest control and disinfection services for residential and commercial properties.', 'insectra' ); ?></p>
        </div>

        <div class="services-grid">
            <?php if ( $services ) :
                foreach ( $services as $s ) :
                    $icon = get_post_meta( $s->ID, '_ins_icon', true ) ?: 'fa-solid fa-shield';
                    $short = get_post_meta( $s->ID, '_ins_short', true ) ?: wp_trim_words( $s->post_content, 18 );
                    ?>
                    <article class="ins-service-card">
                        <span class="corner"></span>
                        <span class="card-icon"><i class="<?php echo esc_attr( $icon ); ?>"></i></span>
                        <h3><a href="<?php echo esc_url( get_permalink( $s ) ); ?>"><?php echo esc_html( $s->post_title ); ?></a></h3>
                        <p><?php echo esc_html( $short ); ?></p>
                        <a class="read-more" href="<?php echo esc_url( get_permalink( $s ) ); ?>">
                            <?php esc_html_e( 'Read More', 'insectra' ); ?>
                            <i class="fa-solid fa-arrow-right arrow"></i>
                        </a>
                    </article>
                <?php endforeach;
            else :
                foreach ( $fallback as $f ) : ?>
                    <article class="ins-service-card">
                        <span class="corner"></span>
                        <span class="card-icon"><i class="<?php echo esc_attr( $f['icon'] ); ?>"></i></span>
                        <h3><a href="#"><?php echo esc_html( $f['title'] ); ?></a></h3>
                        <p><?php echo esc_html( $f['desc'] ); ?></p>
                        <a class="read-more" href="#">
                            <?php esc_html_e( 'Read More', 'insectra' ); ?>
                            <i class="fa-solid fa-arrow-right arrow"></i>
                        </a>
                    </article>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
</section>

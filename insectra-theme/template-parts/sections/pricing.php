<?php
/**
 * Pricing section.
 *
 * @package Insectra
 */
$plans = get_posts( array( 'post_type' => 'ins_pricing', 'posts_per_page' => 3 ) );

$fallback = array(
    array( 'title' => __( 'Basic',    'insectra' ), 'price' => 49,  'currency' => '$', 'period' => __( '/visit',   'insectra' ), 'featured' => 0,
        'features' => array( __( 'One-time inspection', 'insectra' ), __( 'Standard treatment', 'insectra' ), __( 'Email report', 'insectra' ), __( '7-day guarantee', 'insectra' ) ),
    ),
    array( 'title' => __( 'Standard', 'insectra' ), 'price' => 99,  'currency' => '$', 'period' => __( '/month',   'insectra' ), 'featured' => 1,
        'features' => array( __( 'Quarterly visits', 'insectra' ), __( 'All pests covered', 'insectra' ), __( 'Free re-treatment', 'insectra' ), __( '30-day guarantee', 'insectra' ) ),
    ),
    array( 'title' => __( 'Premium',  'insectra' ), 'price' => 199, 'currency' => '$', 'period' => __( '/month',   'insectra' ), 'featured' => 0,
        'features' => array( __( 'Monthly visits', 'insectra' ), __( 'Priority support', 'insectra' ), __( 'All services included', 'insectra' ), __( '90-day guarantee', 'insectra' ) ),
    ),
);
?>
<section class="ins-section ins-pricing" id="pricing">
    <div class="container">
        <div class="section-head text-center">
            <span class="eyebrow"><?php esc_html_e( 'Pricing Plans', 'insectra' ); ?></span>
            <h2 class="section-title"><?php esc_html_e( 'Choose your protection plan', 'insectra' ); ?></h2>
            <p class="section-sub"><?php esc_html_e( 'Transparent pricing with no hidden fees. Cancel anytime.', 'insectra' ); ?></p>
        </div>

        <div class="pricing-grid">
            <?php if ( $plans ) :
                foreach ( $plans as $p ) :
                    $price    = get_post_meta( $p->ID, '_ins_price', true );
                    $currency = get_post_meta( $p->ID, '_ins_currency', true ) ?: '$';
                    $period   = get_post_meta( $p->ID, '_ins_period', true );
                    $cta_url  = get_post_meta( $p->ID, '_ins_cta_url', true ) ?: '#contact';
                    $featured = (int) get_post_meta( $p->ID, '_ins_featured', true );
                    $features = array_filter( array_map( 'trim', explode( "\n", (string) get_post_meta( $p->ID, '_ins_features', true ) ) ) );
                    ?>
                    <article class="ins-pricing-card<?php echo $featured ? ' is-featured' : ''; ?>">
                        <?php if ( $featured ) echo '<span class="ribbon">' . esc_html__( 'Popular', 'insectra' ) . '</span>'; ?>
                        <h3><?php echo esc_html( $p->post_title ); ?></h3>
                        <div class="price"><sup><?php echo esc_html( $currency ); ?></sup><strong><?php echo esc_html( $price ); ?></strong><span><?php echo esc_html( $period ); ?></span></div>
                        <ul class="ins-list">
                            <?php foreach ( $features as $feat ) echo '<li>' . esc_html( $feat ) . '</li>'; ?>
                        </ul>
                        <a class="ins-btn ins-btn-primary" href="<?php echo esc_url( $cta_url ); ?>"><?php esc_html_e( 'Get Started', 'insectra' ); ?> <i class="fa-solid fa-arrow-right arrow"></i></a>
                    </article>
                <?php endforeach;
            else :
                foreach ( $fallback as $p ) : ?>
                    <article class="ins-pricing-card<?php echo $p['featured'] ? ' is-featured' : ''; ?>">
                        <?php if ( $p['featured'] ) echo '<span class="ribbon">' . esc_html__( 'Popular', 'insectra' ) . '</span>'; ?>
                        <h3><?php echo esc_html( $p['title'] ); ?></h3>
                        <div class="price"><sup><?php echo esc_html( $p['currency'] ); ?></sup><strong><?php echo esc_html( $p['price'] ); ?></strong><span><?php echo esc_html( $p['period'] ); ?></span></div>
                        <ul class="ins-list">
                            <?php foreach ( $p['features'] as $f ) echo '<li>' . esc_html( $f ) . '</li>'; ?>
                        </ul>
                        <a class="ins-btn ins-btn-primary" href="#contact"><?php esc_html_e( 'Get Started', 'insectra' ); ?> <i class="fa-solid fa-arrow-right arrow"></i></a>
                    </article>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
</section>

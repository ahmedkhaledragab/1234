<?php
/**
 * Features strip below hero.
 *
 * @package Insectra
 */
$features = array(
    array( 'icon' => 'fa-solid fa-shield-virus', 'title' => __( 'Safe & Approved', 'insectra' ), 'desc' => __( 'EPA-approved chemicals safe for kids and pets.', 'insectra' ) ),
    array( 'icon' => 'fa-solid fa-user-check',   'title' => __( 'Certified Team',  'insectra' ), 'desc' => __( 'Licensed technicians with 10+ years experience.', 'insectra' ) ),
    array( 'icon' => 'fa-solid fa-headset',      'title' => __( '24/7 Support',    'insectra' ), 'desc' => __( 'Emergency response within hours, anywhere.',  'insectra' ) ),
);
?>
<section class="ins-features" id="features">
    <div class="container features-grid">
        <?php foreach ( $features as $f ) : ?>
            <div class="ins-feature">
                <span class="icon"><i class="<?php echo esc_attr( $f['icon'] ); ?>"></i></span>
                <div>
                    <h4><?php echo esc_html( $f['title'] ); ?></h4>
                    <p><?php echo esc_html( $f['desc'] ); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

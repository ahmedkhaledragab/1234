<?php
/**
 * Counter / stats.
 *
 * @package Insectra
 */
$stats = array(
    array( 'num' => 1500, 'suffix' => '+', 'label' => __( 'Happy Clients',     'insectra' ), 'icon' => 'fa-solid fa-face-smile' ),
    array( 'num' => 25,   'suffix' => '+', 'label' => __( 'Expert Team',       'insectra' ), 'icon' => 'fa-solid fa-user-tie' ),
    array( 'num' => 3200, 'suffix' => '+', 'label' => __( 'Projects Completed','insectra' ), 'icon' => 'fa-solid fa-shield-halved' ),
    array( 'num' => 15,   'suffix' => '+', 'label' => __( 'Years Experience',  'insectra' ), 'icon' => 'fa-solid fa-award' ),
);
?>
<section class="ins-counter">
    <div class="container counter-grid">
        <?php foreach ( $stats as $s ) : ?>
            <div class="counter-item">
                <span class="icon"><i class="<?php echo esc_attr( $s['icon'] ); ?>"></i></span>
                <div class="num-wrap">
                    <span class="num" data-target="<?php echo esc_attr( $s['num'] ); ?>">0</span>
                    <span class="suffix"><?php echo esc_html( $s['suffix'] ); ?></span>
                </div>
                <p><?php echo esc_html( $s['label'] ); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

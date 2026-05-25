<?php
/**
 * Projects section.
 *
 * @package Insectra
 */
$projects = get_posts( array( 'post_type' => 'ins_project', 'posts_per_page' => 6 ) );
$fallback = array(
    array( 'title' => __( 'Office Building Disinfection',   'insectra' ), 'cat' => __( 'Disinfection',   'insectra' ), 'icon' => 'fa-solid fa-building' ),
    array( 'title' => __( 'Restaurant Termite Treatment',   'insectra' ), 'cat' => __( 'Termite',        'insectra' ), 'icon' => 'fa-solid fa-utensils' ),
    array( 'title' => __( 'Hotel Mosquito Control',         'insectra' ), 'cat' => __( 'Mosquito',       'insectra' ), 'icon' => 'fa-solid fa-hotel' ),
    array( 'title' => __( 'Warehouse Rodent Removal',       'insectra' ), 'cat' => __( 'Rodent',         'insectra' ), 'icon' => 'fa-solid fa-warehouse' ),
    array( 'title' => __( 'School Cockroach Eradication',   'insectra' ), 'cat' => __( 'Cockroach',      'insectra' ), 'icon' => 'fa-solid fa-school' ),
    array( 'title' => __( 'Apartment Bed-Bug Treatment',    'insectra' ), 'cat' => __( 'Bed-Bug',        'insectra' ), 'icon' => 'fa-solid fa-bed' ),
);
?>
<section class="ins-section ins-projects" id="projects">
    <div class="container">
        <div class="section-head text-center">
            <span class="eyebrow"><?php esc_html_e( 'Recent Projects', 'insectra' ); ?></span>
            <h2 class="section-title"><?php esc_html_e( 'Successful pest control projects', 'insectra' ); ?></h2>
        </div>

        <div class="projects-grid">
            <?php if ( $projects ) :
                foreach ( $projects as $p ) :
                    $cats = wp_get_post_terms( $p->ID, 'ins_project_cat' );
                    $cat  = ! empty( $cats ) ? $cats[0]->name : '';
                    ?>
                    <article class="ins-project-card">
                        <a class="thumb" href="<?php echo esc_url( get_permalink( $p ) ); ?>">
                            <?php if ( has_post_thumbnail( $p ) ) echo get_the_post_thumbnail( $p, 'insectra-blog' ); ?>
                            <span class="overlay"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
                        </a>
                        <div class="card-body">
                            <span class="cat"><?php echo esc_html( $cat ); ?></span>
                            <h3><a href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( $p->post_title ); ?></a></h3>
                        </div>
                    </article>
                <?php endforeach;
            else :
                foreach ( $fallback as $f ) : ?>
                    <article class="ins-project-card">
                        <a class="thumb no-img" href="#">
                            <i class="<?php echo esc_attr( $f['icon'] ); ?>"></i>
                            <span class="overlay"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
                        </a>
                        <div class="card-body">
                            <span class="cat"><?php echo esc_html( $f['cat'] ); ?></span>
                            <h3><a href="#"><?php echo esc_html( $f['title'] ); ?></a></h3>
                        </div>
                    </article>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
</section>

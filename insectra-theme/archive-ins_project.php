<?php
/**
 * Projects archive.
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

<section class="ins-section ins-projects">
    <div class="container">
        <?php
        // Filter by category
        $cats = get_terms( array( 'taxonomy' => 'ins_project_cat', 'hide_empty' => true ) );
        if ( $cats && ! is_wp_error( $cats ) ) : ?>
            <ul class="ins-filter">
                <li><a href="<?php echo esc_url( get_post_type_archive_link( 'ins_project' ) ); ?>" class="active"><?php esc_html_e( 'All', 'insectra' ); ?></a></li>
                <?php foreach ( $cats as $c ) : ?>
                    <li><a href="<?php echo esc_url( get_term_link( $c ) ); ?>"><?php echo esc_html( $c->name ); ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if ( have_posts() ) : ?>
            <div class="projects-grid">
                <?php while ( have_posts() ) : the_post();
                    $cats = get_the_terms( get_the_ID(), 'ins_project_cat' );
                    $cat  = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0]->name : '';
                    ?>
                    <article class="ins-project-card">
                        <a class="thumb<?php echo has_post_thumbnail() ? '' : ' no-img'; ?>" href="<?php the_permalink(); ?>">
                            <?php if ( has_post_thumbnail() ) the_post_thumbnail( 'insectra-blog' ); else echo '<i class="fa-solid fa-folder-open"></i>'; ?>
                            <span class="overlay"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
                        </a>
                        <div class="card-body">
                            <span class="cat"><?php echo esc_html( $cat ); ?></span>
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            <div class="ins-pagination"><?php the_posts_pagination(); ?></div>
        <?php else : ?>
            <p><?php esc_html_e( 'No projects to display.', 'insectra' ); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer();

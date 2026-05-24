<?php
/**
 * Blog card.
 *
 * @package Insectra
 */
?>
<article <?php post_class( 'ins-blog-card' ); ?>>
    <a class="thumb" href="<?php the_permalink(); ?>">
        <?php if ( has_post_thumbnail() ) the_post_thumbnail( 'insectra-blog' ); ?>
        <span class="date">
            <strong><?php echo esc_html( get_the_date( 'd' ) ); ?></strong>
            <small><?php echo esc_html( get_the_date( 'M' ) ); ?></small>
        </span>
    </a>
    <div class="card-body">
        <ul class="meta">
            <li><i class="fa-regular fa-user"></i> <?php the_author(); ?></li>
            <li><i class="fa-regular fa-comment"></i> <?php comments_number( '0', '1', '%' ); ?></li>
        </ul>
        <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <p class="card-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
        <a class="read-more" href="<?php the_permalink(); ?>">
            <?php esc_html_e( 'Read More', 'insectra' ); ?>
            <i class="fa-solid fa-arrow-right arrow"></i>
        </a>
    </div>
</article>

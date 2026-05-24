<?php
/**
 * Comments template.
 *
 * @package Insectra
 */
if ( post_password_required() ) return; ?>
<div id="comments" class="ins-comments">
    <?php if ( have_comments() ) : ?>
        <h3 class="comments-title">
            <?php printf( esc_html( _n( '%s Comment', '%s Comments', get_comments_number(), 'insectra' ) ), number_format_i18n( get_comments_number() ) ); ?>
        </h3>
        <ol class="comment-list">
            <?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true, 'avatar_size' => 56 ) ); ?>
        </ol>
        <?php the_comments_pagination(); ?>
    <?php endif; ?>

    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'insectra' ); ?></p>
    <?php endif; ?>

    <?php comment_form( array( 'class_submit' => 'ins-btn ins-btn-primary' ) ); ?>
</div>

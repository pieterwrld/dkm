<?php
/**
 * Comments template
 *
 * @package DKM
 */

if (post_password_required()) {
    return;
}

if (!have_comments() && !comments_open()) {
    return;
}
?>

<section id="comments" class="comments-area">

    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $count = get_comments_number();
            printf(
                esc_html(_n('%d Reactie', '%d Reacties', $count, 'dkm')),
                (int) $count
            );
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments([
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 48,
                'callback'    => 'dkm_comment_callback',
            ]);
            ?>
        </ol>

        <?php the_comments_navigation(); ?>
    <?php endif; ?>

    <?php if (comments_open()) :
        comment_form([
            'title_reply'          => __('Laat een reactie achter', 'dkm'),
            'title_reply_to'       => __('Reageer op %s', 'dkm'),
            'cancel_reply_link'    => __('Annuleer', 'dkm'),
            'label_submit'         => __('Verstuur', 'dkm'),
            'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s btn btn--gold">%4$s</button>',
            'comment_notes_before' => '',
            'fields'               => [
                'author' => sprintf(
                    '<p class="comment-form-author"><label for="author">%s</label><input id="author" name="author" type="text" value="%s" size="30" required></p>',
                    __('Naam', 'dkm'),
                    esc_attr($commenter['comment_author'] ?? '')
                ),
                'email' => sprintf(
                    '<p class="comment-form-email"><label for="email">%s</label><input id="email" name="email" type="email" value="%s" size="30" required></p>',
                    __('E-mail', 'dkm'),
                    esc_attr($commenter['comment_author_email'] ?? '')
                ),
            ],
            'comment_field' => sprintf(
                '<p class="comment-form-comment"><label for="comment">%s</label><textarea id="comment" name="comment" cols="45" rows="6" required></textarea></p>',
                __('Reactie', 'dkm')
            ),
        ]);
    endif; ?>

</section>
<?php

function dkm_comment_callback($comment, $args, $depth): void {
    $tag = ($args['style'] === 'div') ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class('comment'); ?>>
        <article class="comment__body">
            <header class="comment__header">
                <?php echo get_avatar($comment, 48, '', '', ['class' => 'comment__avatar']); ?>
                <div class="comment__meta">
                    <strong class="comment__author"><?php comment_author(); ?></strong>
                    <time class="comment__date" datetime="<?php comment_date('c'); ?>">
                        <?php printf('%s om %s', get_comment_date(), get_comment_time()); ?>
                    </time>
                </div>
            </header>

            <?php if ($comment->comment_approved === '0') : ?>
                <p class="comment__moderation"><?php esc_html_e('Je reactie wacht op goedkeuring.', 'dkm'); ?></p>
            <?php endif; ?>

            <div class="comment__content">
                <?php comment_text(); ?>
            </div>

            <?php
            comment_reply_link(array_merge($args, [
                'depth'     => $depth,
                'max_depth' => $args['max_depth'],
                'before'    => '<div class="comment__reply">',
                'after'     => '</div>',
            ]));
            ?>
        </article>
    <?php
}

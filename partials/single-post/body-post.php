   
<?php
/**
 * 
 * Partial Name: body-post
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$author_id = get_post_field('post_author', get_the_id());
$author_name = get_the_author_meta('display_name', $author_id);
$author_avatar = get_avatar($author_id, 96);
$share_post = get_field('share_post', 'option');
?>
<section class="body-post-partial-e7073e">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="content">
                    <?= the_content(); ?>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="author">
                    <div class="author-avatar">
                        <?php echo $author_avatar; ?>
                    </div>
                    <div class="author-info">
                        <p><strong><?php echo esc_html($author_name); ?></strong></p>
                    </div>
                </div>
                <hr>
                <div class="shares-contain">
                    <span class="label"><?= $share_post['text']; ?></span>
                    <div class="shares">
                        <?= do_shortcode($share_post['shortcode']); ?>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <?php get_template_part('partials/single-post/gallery'); ?>
                <?php get_template_part('partials/single-post/after-gallery'); ?>
            </div>
        </div>
    </div>
</section>
                    
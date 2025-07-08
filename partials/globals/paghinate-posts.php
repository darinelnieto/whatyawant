   
<?php
/**
 * 
 * Partial Name: paghinate-posts
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$prev_post = get_previous_post();
$next_post = get_next_post();
?>
<section class="paghinate-posts-partial-a025f6">
    <div class="row justify-content-between">
        <div class="col-3 col-md-2 col-lg-1 text-center">
            <?php if ($prev_post): ?>
                <a href="<?php echo get_permalink($prev_post->ID); ?>" class="prev">
                    <i class="fa-solid fa-chevron-left"></i> <span>PREV</span>
                </a>
            <?php endif; ?>
        </div>
        <div class="col-3 col-md-2 col-lg-1 text-center">
            <?php if ($next_post): ?>
                <a href="<?php echo get_permalink($next_post->ID); ?>" class="next">
                    <span>NEXT</span> <i class="fa-solid fa-chevron-right"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>
                    
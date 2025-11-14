   
<?php
/**
 * 
 * Partial Name: instagram
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$instagram = get_field('instagram_content', 'option');
?>
<section class="instagram-partial-d57523">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2><?= $instagram['title']; ?></h2>
                <hr>
                <div class="instagram">
                    <?= do_shortcode($instagram['shortcode_instagram']); ?>
                </div>
            </div>
        </div>
    </div>
</section>
                    
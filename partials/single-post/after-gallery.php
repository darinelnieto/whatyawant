   
<?php
/**
 * 
 * Partial Name: after-gallery
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$enable_extra_content = get_field('enable_extra_content');
if($enable_extra_content === true):
?>
<section class="after-gallery-partial-89aa98">
    <?= get_field('extra_content'); ?>
</section>
<?php endif; ?>
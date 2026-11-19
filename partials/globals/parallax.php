   
<?php
/**
 * 
 * Partial Name: parallax
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$parallax = get_field('wyw_parallax');
?>
<section class="parallax-partial-82f3ef" style="background-image:url(<?= $parallax['background_image']['url']; ?>);">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="content">
                    <?= wp_get_attachment_image($parallax['main_image']['ID'] ?? '', 'large', false, array(
                        'class' => 'img-full',
                        'loading' => 'lazy',
                        'decoding' => 'async'
                    )); ?>
                    <p><?= $parallax['text']; ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
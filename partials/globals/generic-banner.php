   
<?php
/**
 * 
 * Partial Name: generic-banner
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$image = get_field('main_image');
$h1 = get_field('title_page');
$description = get_field('text_after_title');
?>
<section class="generic-banner-partial-ef644c">
    <?php if(!empty($image)): ?>
        <div class="banner">
            <?= wp_get_attachment_image($image['ID'] ?? '', 'full', false, array(
                'class' => 'main-image',
                'sizes' => '100vw',
                'fetchpriority' => 'high',
                'loading' => 'eager',
                'decoding' => 'async',
            )) ?>
            <div class="text-content">
                <?php if(!empty($h1)): ?>
                    <h1><?= $h1; ?></h1>
                <?php endif; if(!empty($description)): ?>
                    <p><?= $description; ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="migas">
        <div class="row justify-content-between">
            <div class="col-12 col-md-3">
                <p class="name-page"><?= the_title(); ?></p>
            </div>
            <div class="col-12 col-md-4">
                <?= mi_breadcrumb(); ?>
            </div>
        </div>
    </div>
</section>
                    
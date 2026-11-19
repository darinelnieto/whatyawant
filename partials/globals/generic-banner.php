   
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

// Checkbox "Slide" activo: galería en slide; si no, la imagen única de siempre
$banner_gallery = get_field('enable_slide') ? get_field('banner_gallery') : array();
$is_slide = !empty($banner_gallery) && is_array($banner_gallery);
?>
<section class="generic-banner-partial-ef644c">
    <?php if($is_slide || !empty($image)): ?>
        <div class="banner<?= $is_slide ? ' is-slide' : ''; ?>">
            <?php if($is_slide): ?>
                <div class="generic-banner-slider owl-carousel">
                    <?php foreach($banner_gallery as $index => $slide): $is_first = $index === 0; ?>
                        <div class="item">
                            <?= wp_get_attachment_image($slide['ID'] ?? '', 'full', false, array(
                                'class' => 'main-image',
                                'alt' => $slide['alt'] ?: ($h1 ?: get_the_title()),
                                'sizes' => '100vw',
                                // Solo la primera es el LCP; el resto se carga diferido
                                'fetchpriority' => $is_first ? 'high' : 'low',
                                'loading' => $is_first ? 'eager' : 'lazy',
                                'decoding' => 'async',
                            )) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <?= wp_get_attachment_image($image['ID'] ?? '', 'full', false, array(
                    'class' => 'main-image',
                    'sizes' => '100vw',
                    'fetchpriority' => 'high',
                    'loading' => 'eager',
                    'decoding' => 'async',
                )) ?>
            <?php endif; ?>
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
                    
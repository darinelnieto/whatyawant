<?php
/**
 *
 * Partial Name: gallery
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$enable_gallery = get_field('enable_gallery');
$images = get_field('galeria');
$marca = get_template_directory_uri().'/images/logo-marca-de-agua-grid.png';
$marca_popup = get_template_directory_uri().'/images/logo-marca-de-agua-popup.png';
?>
<section class="gallery-partial-3a2065<?= $enable_gallery ? ' js-gallery' : ''; ?>" data-watermark-grid="<?= esc_url($marca); ?>" data-watermark-popup="<?= esc_url($marca_popup); ?>">
    <?php if(!empty($enable_gallery) && !empty($images)):
        // Sin srcset: la marca de agua se dibuja en px sobre el tamaño exacto de la imagen
        add_filter('wp_calculate_image_srcset_meta', '__return_empty_array');
    ?>
    <div class="modal-gallery" role="dialog" aria-modal="true" aria-label="Galería de imágenes" aria-hidden="true">
        <span class="modal-gallery-overlay close-modal" aria-hidden="true"></span>
        <a href="#" class="close-modal" role="button" aria-label="Cerrar galería">&times;</a>
        <div class="content">
            <?php foreach($images as $img): ?>
                <div class="item-slide<?= $img['height'] > $img['width'] ? ' content-img-slider-vertical' : ''; ?>">
                    <?= wp_get_attachment_image($img['ID'] ?? '', 'medium_large', false, array(
                        'class' => 'img-modal-slider',
                        'loading' => 'lazy',
                        'decoding' => 'async',
                        'alt' => $img['alt'] ?: $img['title'],
                    )) ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="nav-gallery">
            <a href="#" class="prev" role="button" aria-label="Imagen anterior">&#10094;</a>
            <a href="#" class="next" role="button" aria-label="Imagen siguiente">&#10095;</a>
        </div>
    </div>
    <div class="mt-4" id="gallery__grid">
        <?php foreach ($images as $i => $image_url): ?>
            <div class="<?= $image_url['height'] > $image_url['width'] ? 'vertical-img-content' : 'gallery-item-contain'; ?>">
                <?= wp_get_attachment_image($image_url['ID'] ?? '', 'medium', false, array(
                    'class' => 'el_image gallery-item',
                    'loading' => 'lazy',
                    'decoding' => 'async',
                    'alt' => $image_url['alt'] ?: $image_url['title'],
                    'data-index' => $i + 1,
                    'role' => 'button',
                    'tabindex' => '0',
                )) ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
        remove_filter('wp_calculate_image_srcset_meta', '__return_empty_array');
    endif; ?>
</section>

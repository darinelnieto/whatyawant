   
<?php
/**
 * 
 * Partial Name: banner
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$banner = get_field('slide_banner');
if(!empty($banner)):
?>
<section class="banner-partial-0d4ebd owl-carousel">
    <?php foreach($banner as $index => $item): $is_first = $index === 0; ?>
        <div class="item">
            <?php if(!empty($item['image'])): ?>
                <?= wp_get_attachment_image($item['image']['ID'] ?? '', 'full', false, array(
                    'class' => 'banner-image',
                    'sizes' => '100vw',
                    // Solo el primer slide es el LCP; el resto se carga diferido
                    'fetchpriority' => $is_first ? 'high' : 'low',
                    'loading' => $is_first ? 'eager' : 'lazy',
                    'decoding' => 'async',
                )) ?>
            <?php endif; ?>
            <div class="tecxt-contain">
                <?php if(!empty($item['title'])): ?>
                    <?php $heading = $is_first ? 'h1' : 'h2'; ?>
                    <<?= $heading; ?>><?= $item['title']; ?></<?= $heading; ?>>
                <?php endif;  if(!empty($item['description'])): ?>
                    <div class="description">
                        <?= $item['description']; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</section>
<?php endif; ?>          
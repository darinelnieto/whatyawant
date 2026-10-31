<?php
/**
 *
 * Partial Name: zones-categories
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$content = get_field('categories');
if(!empty($content)): foreach($content as $item):
    $is_slider = !empty($item['multiple_images']) && !empty($item['gallery_images']);
?>
<section class="zones-categories-partial-6c7844">
    <?php if($is_slider): ?>
        <div class="zones-slider owl-carousel">
            <?php foreach($item['gallery_images'] as $image): ?>
                <div class="item">
                    <?= wp_get_attachment_image($image['ID'] ?? '', 'full', false, array(
                        'class' => 'main-image',
                        'sizes' => '100vw',
                        'loading' => 'lazy',
                        'decoding' => 'async'
                    )) ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <?= wp_get_attachment_image($item['main_image']['ID'] ?? '', 'large', false, array(
            'class' => 'main-image',
            'loading' => 'lazy',
            'decoding' => 'async'
        )) ?>
    <?php endif; ?>
    <div class="text-contain">
        <h2><?= $item['banner_text']; ?></h2>
        <p><?= $item['description']; ?></p>
        <?php if(!empty($item['page_link'])): ?>
            <a href="<?= $item['page_link'] ?>">
                <?= $item['cta_text']; ?>
            </a>
        <?php endif; ?>
    </div>
</section>
<?php endforeach; endif; ?>

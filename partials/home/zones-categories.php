   
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
if($content): foreach($content as $item):
?>
<section class="zones-categories-partial-6c7844">
    <img src="<?= $item['main_image']['url'] ?>" alt="<?= $item['main_image']['title']; ?>" width="<?= $item['main_image']['width']; ?>" height="<?= $item['main_image']['height'] ?>" class="main-image">
    <div class="text-contain">
        <h2><?= $item['banner_text']; ?></h2>
        <p><?= $item['description']; ?></p>
        <?php if($item['page_link']): ?>
            <a href="<?= $item['page_link'] ?>">
                <?= $item['cta_text']; ?>
            </a>
        <?php endif; ?>
    </div>
</section>
<?php endforeach; endif; ?>                    
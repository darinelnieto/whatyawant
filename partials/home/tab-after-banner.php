   
<?php
/**
 * 
 * Partial Name: tab-after-banner
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$tabs = get_field('tabs_after_banner');
if($tabs):
?>
<section class="tab-after-banner-partial-42a2b3">
    <div class="container">
        <div class="row">
            <?php foreach($tabs as $item): ?>
                <div class="col-12 col-md-4 mb-5 mb-md-0 text-center">
                    <?php if($item['icon']): ?>
                        <img src="<?= $item['icon']['url']; ?>" alt="<?= $item['icon']['title']; ?>" width="<?= $item['icon']['width']; ?>" height="<?= $item['icon']['height']; ?>" class="icon">
                    <?php endif; if($item['title']): ?>
                        <h3><?= $item['title'] ?></h3>
                    <?php endif; if($item['description']): ?>
                        <div class="description">
                            <?= $item['description']; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>        
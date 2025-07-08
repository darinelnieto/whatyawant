   
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
    <?php if($image): ?>
        <div class="banner">
            <img src="<?= $image['url']; ?>" alt="<?= $image['title']; ?>" width="<?= $image['width']; ?>" height="<?= $image['height']; ?>" class="main-image">
            <div class="text-content">
                <?php if($h1): ?>
                    <h1><?= $h1; ?></h1>
                <?php endif; if($description): ?>
                    <p><?= $description; ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="migas">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-12 col-md-3">
                    <p class="name-page"><?= the_title(); ?></p>
                </div>
                <div class="col-12 col-md-4">
                    <?= mi_breadcrumb(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
                    
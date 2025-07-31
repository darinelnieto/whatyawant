   
<?php
/**
 * 
 * Partial Name: rent-my-property
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$content = get_field('i_want_to_rent_my_property');
?>
<section class="rent-my-property-partial-ad18e5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <?php if($content['title']): ?>
                    <h2><?= $content['title']; ?></h2>
                <?php endif; if($content['cta_link']): ?>
                    <a href="<?= $content['cta_link']; ?>" target="_blank">
                        <span><?= $content['cta_text']; ?></span>
                    </a>
                <?php endif; if($content['description']): ?>
                    <p><?= $content['description']; ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
                    
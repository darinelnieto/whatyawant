   
<?php
/**
 * 
 * Partial Name: wyw-text-content
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$content = get_field('wyw_text_content');
?>
<section class="wyw-text-content-partial-fa9603">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-9 text-center">
                <?php if(!empty($content['title'])): ?>
                    <h2 class="title"><?= $content['title']; ?></h2>
                <?php endif; if(!empty($content['text'])): ?>
                    <div class="description"><?= $content['text'] ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
                    
   
<?php
/**
 * 
 * Partial Name: parallax
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$parallax = get_field('wyw_parallax');
?>
<section class="parallax-partial-82f3ef" style="background-image:url(<?= $parallax['background_image']['url']; ?>);">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="content">
                    <img src="<?= $parallax['main_image']['url']; ?>" alt="<?= $parallax['main_image']['title']; ?>" width="<?= $parallax['main_image']['width']; ?>" height="<?= $parallax['main_image']['height']; ?>">
                    <p><?= $parallax['text']; ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
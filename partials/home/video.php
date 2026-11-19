   
<?php
/**
 * 
 * Partial Name: video
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$video = get_field('video');
if(!empty($video)):
?>
<section class="video-partial-d34ac9">
    <video autoplay muted loop playsinline preload="metadata" aria-hidden="true" style="width: 100%; height: auto; object-fit: cover;">
        <source src="<?= $video['the_video']; ?>" type="video/mp4">
        Tu navegador no soporta video HTML5.
    </video>
    <?= wp_get_attachment_image($video['text_image']['ID'] ?? '', 'large', false, array(
        'class' => 'text-img',
        'loading' => 'lazy',
        'decoding' => 'async'
    )) ?>
</section>
<?php endif; ?>
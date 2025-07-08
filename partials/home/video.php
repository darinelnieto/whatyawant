   
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
if($video):
?>
<section class="video-partial-d34ac9">
    <video autoplay muted loop playsinline preload="auto" style="width: 100%; height: auto; object-fit: cover;">
        <source src="<?= $video['the_video']; ?>" type="video/mp4">
        Tu navegador no soporta video HTML5.
    </video>
    <img src="<?= $video['text_image']['url']; ?>" alt="<?= $video['text_image']['title']; ?>" width="<?= $video['text_image']['width']; ?>" height="<?= $video['text_image']['height']; ?>" class="text-img">
</section>
<?php endif; ?>
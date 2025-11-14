   
<?php
/**
 * 
 * Partial Name: playlist
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$playlist = get_field('playlist_content');
if($playlist['list']):
?>
<section class="playlist-partial-68e5ee">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2><?= $playlist['title']; ?></h2>
            </div>
            <?php foreach($playlist['list'] as $item): ?>
                    <div class="col-12 col-md-5">
                        <div class="content-playlist">
                            <a href="<?= get_field('link', $item['item']->ID)['url']; ?>" target="_blank">
                                <img src="<?= get_field('imagen',$item['item']->ID)['url']; ?>" alt="" class="image-playlist">
                                <h4><?= get_the_title($item['item']->ID); ?></h4>
                            </a>
                        </div>
                    </div>
            <?php endforeach; ?>
            <div class="col-12 col-md-10">
                <hr>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
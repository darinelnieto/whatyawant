   
<?php
/**
 * 
 * Partial Name: banner-post
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$thumb_id = get_post_thumbnail_id(get_the_ID());
$taxonomia = 'category';
$terminos_asignados = get_the_terms(get_the_id(), $taxonomia);
$autor_id = get_post_field('post_author', get_the_id());
$autor_nombre = get_the_author_meta('first_name', $autor_id);
$autor_last_name = get_the_author_meta('last_name', $autor_id);
?>
<section class="banner-post-partial-824aec">
    <?= wp_get_attachment_image($thumb_id, 'full', false, array(
        'class' => 'main-image',
        'alt' => get_the_title(),
        'sizes' => '100vw',
        'loading' => 'eager',
        'fetchpriority' => 'high',
        'decoding' => 'async'
    )) ?>
    <div class="text-content">
        <h1><?= the_title(); ?></h1>
        <p><?= get_the_date('j F, Y'); ?> | <?php if(!empty($terminos_asignados)){ echo 'in ' . $terminos_asignados[0]->name; } ?> | BY <?= $autor_nombre.' '.$autor_last_name; ?></p>
    </div>
</section>
                    
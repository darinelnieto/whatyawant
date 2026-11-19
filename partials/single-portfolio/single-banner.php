<?php
$post_id = get_the_ID();
$taxonomia = 'portfolio_cat';
$terminos_asignados = get_the_terms($post_id, $taxonomia);

ob_start(); // ← Iniciar captura de HTML
if (!empty($terminos_asignados) && !is_wp_error($terminos_asignados)) {
    $todos_los_terminos = [];

    foreach ($terminos_asignados as $term) {
        $todos_los_terminos[$term->term_id] = $term;

        $parent = $term->parent;
        while ($parent && !isset($todos_los_terminos[$parent])) {
            $parent_term = get_term($parent, $taxonomia);
            if (is_wp_error($parent_term)) break;
            $todos_los_terminos[$parent_term->term_id] = $parent_term;
            $parent = $parent_term->parent;
        }
    }

    $por_padre = [];
    foreach ($todos_los_terminos as $term) {
        $por_padre[$term->parent][] = $term;
    }

    function mostrar_terminos_en_arbol($padre_id, $por_padre, $nivel = 0) {
        if (!isset($por_padre[$padre_id])) return;
        foreach ($por_padre[$padre_id] as $term) {
            if($term->slug !== 'destacados'){
                echo '<span>' . str_repeat('', $nivel) . esc_html($term->name) . '</span><span class"coma">, </span>';
                mostrar_terminos_en_arbol($term->term_id, $por_padre, $nivel + 1);
            }
        }
    }

    mostrar_terminos_en_arbol(0, $por_padre);
    $html_terminos = ob_get_clean(); // ← Guardar el HTML
} else {
    $html_terminos = 'Este portfolio no tiene categorías asignadas.';
}
/* Banner */
$thumb_id = get_post_thumbnail_id(get_the_ID());

// En propiedades vacacionales el banner es un slide: imagen destacada + primeras 5 de la galería
$slide_images = array();
if (get_field('is_vacant_properties') === true) {
    $galeria = get_field('galeria');
    if (!empty($galeria) && is_array($galeria)) {
        foreach ($galeria as $img) {
            if (empty($img['ID']) || (int) $img['ID'] === (int) $thumb_id) continue;
            $slide_images[] = $img;
            if (count($slide_images) === 5) break;
        }
    }
}
$is_slider = !empty($slide_images);
$marca_banner = get_template_directory_uri().'/images/logo-marca-de-agua-popup.png';
?>

<section class="single-banner-partial-1c9173<?= $is_slider ? ' is-slider' : ''; ?>">
    <?php if ($is_slider): ?>
        <div class="single-banner-slider owl-carousel" data-watermark="<?= esc_url($marca_banner); ?>">
            <div class="item">
                <?= wp_get_attachment_image($thumb_id, 'full', false, array(
                    'class' => 'main-image js-banner-watermark',
                    'alt' => get_the_title(),
                    'sizes' => '100vw',
                    'loading' => 'eager',
                    'fetchpriority' => 'high',
                    'decoding' => 'async'
                )) ?>
            </div>
            <?php
            // Con srcset: el navegador elige la resolución adecuada y la marca se dibuja sobre esa
            foreach ($slide_images as $img): ?>
                <div class="item">
                    <?= wp_get_attachment_image($img['ID'], 'full', false, array(
                        'class' => 'main-image js-banner-watermark',
                        'alt' => $img['alt'] ?: get_the_title(),
                        'sizes' => '100vw',
                        'loading' => 'lazy',
                        'fetchpriority' => 'low',
                        'decoding' => 'async'
                    )) ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <?= wp_get_attachment_image($thumb_id, 'full', false, array(
            'class' => 'main-image',
            'alt' => get_the_title(),
            'sizes' => '100vw',
            'loading' => 'eager',
            'fetchpriority' => 'high',
            'decoding' => 'async'
        )) ?>
    <?php endif; ?>
    <div class="text-content">
        <h1><?= the_title(); ?></h1>
        <p>In <?= $html_terminos; ?></p>
    </div>
</section>

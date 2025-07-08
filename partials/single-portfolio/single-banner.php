<?php
$post_id = get_the_ID();
$taxonomia = 'portfolio_cat';
$terminos_asignados = get_the_terms($post_id, $taxonomia);

ob_start(); // ← Iniciar captura de HTML
if ($terminos_asignados && !is_wp_error($terminos_asignados)) {
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
$image = wp_get_attachment_image_src($thumb_id, 'full');
?>

<section class="single-banner-partial-1c9173" onclick="openModal();">
    <img src="<?= $image[0]; ?>" alt="<?= get_the_title(); ?>" width="<?= $image[1]; ?>" height="<?= $image[2]; ?>" class="main-image">
    <div class="text-content">
        <h1><?= the_title(); ?></h1>
        <p>In <?= $html_terminos; ?></p>
    </div>
</section>
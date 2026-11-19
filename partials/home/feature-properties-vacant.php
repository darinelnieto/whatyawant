<?php
/**
 * 
 * Partial Name: feature-properties-vacant
 * Copia de feature-properties para propiedades sin amoblar:
 * tres filtros (disponibilidad, habitaciones, rango de precio) en dropdowns.
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$properties = get_field('properties');
$availability = get_terms(['taxonomy' => 'availability_cat']);
$rooms = get_terms(['taxonomy' => 'number_of_rooms']);
$price_ranges = get_terms(['taxonomy' => 'price_range', 'hide_empty' => false, 'orderby' => 'term_id', 'order' => 'ASC']);
$disponibilidad = ['Disponible', 'No Disponible', 'Proximamente'];
?>
<section class="feature-properties-partial-e14bf5 feature-properties-vacant">
    <div class="container">
        <div class="row justify-content-center">
            <?php if(!empty($properties['title']) || !empty($properties['description'])): ?>
                <div class="col-12 text-center title-content">
                    <h2><?= $properties['title'] ?? ''; ?></h2>
                    <p class="description"><?= $properties['description'] ?? ''; ?></p>
                    <hr>
                </div>
            <?php endif; ?>
            <div class="col-12 col-lg-10">
                <div class="row" id="filters">
                    <?php if(!empty($availability)): $key = -1; ?>
                        <div class="col-12 col-md-4 filter">
                            <h3 role="button" tabindex="0" aria-expanded="false">Disponibilidad <span class="count"></span></h3>
                            <ul class="availability">
                                <?php foreach($availability as $li): $key++;  ?>
                                    <li class="item">
                                        <a href="<?= esc_attr($li->slug); ?>">
                                            <span class="checkbox"></span> <?= $disponibilidad[$key] ?? esc_html($li->name); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; if(!empty($rooms)): ?>
                        <div class="col-12 col-md-4 filter">
                            <h3 role="button" tabindex="0" aria-expanded="false">Habitaciones <span class="count"></span></h3>
                            <ul class="rooms">
                                <?php foreach($rooms as $li): ?>
                                    <li class="item">
                                        <a href="<?= esc_attr($li->slug); ?>">
                                            <span class="checkbox"></span>
                                            <span class="text"><?php if($li->name !== '5'){ echo $li->name; }else{ echo '<span class="more">+</span> '; } ?> <?php if($li->name === '1'): ?>Habitación<?php else: ?>Habitaciones<?php endif; ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; if(!empty($price_ranges) && !is_wp_error($price_ranges)): ?>
                        <div class="col-12 col-md-4 filter">
                            <h3 role="button" tabindex="0" aria-expanded="false">Rango de precio <span class="count"></span></h3>
                            <ul class="price-range">
                                <?php foreach($price_ranges as $li): ?>
                                    <li class="item">
                                        <a href="<?= esc_attr($li->slug); ?>">
                                            <span class="checkbox"></span>
                                            <span class="text"><?= esc_html($li->name); ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="row" id="contenedor-posts" aria-live="polite" data-endpoint="<?= esc_url(rest_url('relatos/list')); ?>" data-category="<?= esc_attr(!empty($properties['select_category']) ? $properties['select_category']->slug : ''); ?>" data-vacant="1"></div>
    </div>
</section>

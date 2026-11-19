   
<?php
/**
 * 
 * Partial Name: feature-properties
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$properties = get_field('properties');
$availability = get_terms(['taxonomy' => 'availability_cat']);
$rooms = get_terms(['taxonomy' => 'number_of_rooms']);
$disponibilidad = ['Disponible', 'No Disponible', 'Proximamente'];
?>
<section class="feature-properties-partial-e14bf5">
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
                <div class="row justify-content-between" id="filters">
                    <?php if(!empty($availability)): $key = -1; ?>
                        <div class="col-6 col-md-5 filter">
                            <h3>Disponibilidad</h3>
                            <ul class="availability">
                                <?php foreach($availability as $li): $key++;  ?>
                                    <li class="item">
                                        <a href="<?= $li->slug; ?>">
                                            <span class="checkbox"></span> <?= $disponibilidad[$key]; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; if(!empty($rooms)): ?>
                        <div class="col-6 col-md-7 filter">
                            <h3>Habitaciones</h3>
                            <ul class="rooms">
                                <?php foreach($rooms as $li): ?>
                                    <li class="item">
                                        <a href="<?= $li->slug; ?>">
                                            <span class="checkbox"></span>
                                            <span class="text"><?php if($li->name !== '5'){ echo $li->name; }else{ echo '<span class="more">+</span> '; } ?> <?php if($li->name === '1'): ?>Habitación<?php else: ?>Habitaciones<?php endif; ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="row" id="contenedor-posts" aria-live="polite" data-endpoint="<?= esc_url(rest_url('relatos/list')); ?>" data-category="<?= esc_attr(!empty($properties['select_category']) ? $properties['select_category']->slug : ''); ?>"></div>
    </div>
</section>

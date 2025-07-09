   
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
?>
<section class="feature-properties-partial-e14bf5">
    <div class="container">
        <div class="row justify-content-center">
            <?php if($properties['title'] || $properties['description']): ?>
                <div class="col-12 text-center title-content">
                    <h2><?= $properties['title']; ?></h2>
                    <p class="description"><?= $properties['description']; ?></p>
                    <hr>
                </div>
            <?php endif; ?>
            <div class="col-12 col-lg-10">
                <div class="row" id="filters">
                    <?php if($availability): ?>
                        <div class="col-6 filter">
                            <h3>Disponibilidad</h3>
                            <ul class="availability">
                                <?php foreach($availability as $li): ?>
                                    <li class="item">
                                        <a href="<?= $li->slug; ?>">
                                            <span class="checkbox"></span> <?= $li->name; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; if($rooms): ?>
                        <div class="col-6 filter">
                            <h3>Habitaciones</h3>
                            <ul class="rooms">
                                <?php foreach($rooms as $li): ?>
                                    <li class="item">
                                        <a href="<?= $li->slug; ?>">
                                            <span class="checkbox"></span>
                                            <span class="text"><?= $li->name; ?> <?php if($li->name === '1'): ?>Habitación<?php else: ?>Habitaciones<?php endif; ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="row" id="contenedor-posts"></div>
    </div>
</section>
<script>
    const rout = _dittoURL_ + "/wp-json/relatos/list";
    console.log(rout);
    <?php if($properties['select_category']): ?>
        const category = '<?= $properties['select_category']->slug; ?>';
    <?php else: ?>
        const category = '';
    <?php endif; ?>
</script>
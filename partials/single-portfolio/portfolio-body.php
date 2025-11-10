   
<?php
/**
 * 
 * Partial Name: portfolio-body
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$information = get_field('information');
$property_details = get_field('property_details');
$property_characteristics = get_field('property_characteristics');
$basic_rules_of_use = get_field('basic_rules_of_use', 'option');
$cancellations = get_field('cancellations', 'option');
$share_post = get_field('share_post', 'option');
?>
<section class="portfolio-body-partial-59cf78">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-5 mb-4 mb-md-0">
                <div class="prices-content">
                    <?php if(get_field('valor')): ?>
                        <h2 class="price"><?php if(get_field('valor') !== 'Según Temporada'): ?>$<?php endif; echo get_field('valor'); ?></h2>
                    <?php endif; if($information['price_text']): ?>
                        <p class="text-price"><?= $information['price_text']; ?></p>
                    <?php endif; if($information['enable_price_list']): ?>
                        <ul class="complement-prices">
                            <?php foreach($information['manual_price_list'] as $item): ?>
                                <li class="complement-item">
                                    <strong><?= $item['name']; ?></strong> <?= $item['value']; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <ul class="complement-prices">
                            <li class="complement-item">
                                <strong>Depósito de seguridad:</strong> <?= $information['deposit_value']; ?>
                            </li>
                            <li class="complement-item">
                                <strong>Tarifa de limpieza:</strong> <?= $information['cleaning_fee']; ?>
                            </li>
                        </ul>
                    <?php endif; ?>
                    <hr>
                </div>
                <div class="description">
                    <?= get_field('description'); ?>
                </div>
                <hr>
                <div class="global-information">
                    <?php if($basic_rules_of_use['title'] && $basic_rules_of_use['description']): ?>
                        <div class="basic_rules_of_use">
                            <h3><?= $basic_rules_of_use['title']; ?></h3>
                            <hr class="after-title">
                            <div class="description">
                                <?= $basic_rules_of_use['description']; ?>
                            </div>
                        </div>
                    <?php endif; if($cancellations['title'] && $cancellations['description']): ?>
                        <div class="cancellations">
                            <h3><?= $cancellations['title']; ?></h3>
                            <hr class="after-title">
                            <div class="description">
                                <?= $cancellations['description']; ?>
                            </div>
                        </div>
                    <?php endif; if($share_post['text'] && $share_post['shortcode']): ?>
                        <hr>
                        <div class="shares-contain">
                            <span class="label"><?= $share_post['text']; ?></span>
                            <div class="shares">
                                <?= do_shortcode($share_post['shortcode']); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-5">
                <div class="gray-content">
                    <div class="content-yellow">
                        <span><?= $information['messagebox_yellow']; ?></span>
                    </div>
                    <hr>
                    <?php if($property_details): ?>
                        <div class="property-details">
                            <h3>Detalles de la propiedad</h3>
                            <hr class="after-title">
                            <ul>
                                <?php foreach($property_details as $item): ?>
                                    <li>
                                       <strong><?= $item['name']; ?> </strong> <?= $item['value']; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; if($property_characteristics): ?>
                        <div class="property-characteristics">
                            <h3>Características de la propiedad</h3>
                            <hr class="after-title">
                            <ul>
                                <?php foreach($property_characteristics as $item): ?>
                                    <li>
                                        <i class="fa-solid fa-check"></i>
                                        <strong><?= $item['item']->post_title; ?></strong>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
                    
   
<?php
/**
 * 
 * Partial Name: header-content
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$image = get_field('main_image');
// En 404 y singles (propiedad / blog) el header siempre es fijo
$not_fixed = !$image && !is_front_page() && !is_404() && !is_singular(array('portfolio', 'post'));
?>
<section class="header-content-partial-1bbfc7 <?php if(!empty($not_fixed)): ?>not-fixed<?php endif; ?>">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-5 col-md-2 p-0">
                <div class="logo-contain">
                    <?= get_custom_logo(); ?>
                </div>
            </div>
            <div class="col-7 col-md-10">
                <div class="bar-menu" role="button" tabindex="0" aria-label="Abrir menú" aria-expanded="false" aria-controls="main-menu">
                    <span class="top"></span>
                    <span class="center"></span>
                    <span class="bottom"></span>
                </div>
                <nav class="menu-contain" id="main-menu" aria-label="Menú principal">
                    <?php wp_nav_menu(['menu' => 'Menu 1']); ?>
                </nav>
            </div>
        </div>
    </div>
</section>
   
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
?>
<section class="header-content-partial-1bbfc7 <?php if(!$image && is_front_page() === false): ?>not-fixed<?php endif; ?>">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-5 col-md-2 p-0">
                <div class="logo-contain">
                    <?= get_custom_logo(); ?>
                </div>
            </div>
            <div class="col-7 col-md-10">
                <div class="bar-menu">
                    <span class="top"></span>
                    <span class="center"></span>
                    <span class="bottom"></span>
                </div>
                <div class="menu-contain">
                    <?php wp_nav_menu(['menu' => 'Menu 1']); ?>
                </div>
            </div>
        </div>
    </div>
</section>
                    
   
<?php
/**
 * 
 * Partial Name: map
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$map = get_field('iframe_mapa');
if($map):
?>
<section class="map-partial-fee1c0">
    <?= $map; ?>
</section>
<?php endif; ?>              
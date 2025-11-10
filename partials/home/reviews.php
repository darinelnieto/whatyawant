   <?php
/**
 * 
 * Partial Name: reviews
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$reviews = get_field('reviews');
?>
<section class="reviews-partial-19bdc6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-11 text-center">
                <?= do_shortcode($reviews); ?>
            </div>
        </div>
    </div>
</section>
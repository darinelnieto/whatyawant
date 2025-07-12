   
<?php
/**
 * 
 * Template Name: reviews-page
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
get_header();
$reviews = get_field('reviews');
$shortcode_form = get_field('shortcode_form');
?>
<main id="reviews-page-template-1dd224">
    <?php get_template_part('partials/globals/generic-banner'); ?>
    <section class="reviews">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-11">
                    <div class="reviews-content">
                        <?= do_shortcode($reviews); ?>
                    </div>
                    <div class="form">
                        <div class="title">
                            <h2><?= get_field('title_form'); ?></h2>
                            <hr>
                        </div>
                        <?= do_shortcode($shortcode_form); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
                    
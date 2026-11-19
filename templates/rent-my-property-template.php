   
<?php
/**
 * 
 * Template Name: rent-my-property
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
get_header();
?>
<main id="rent-my-property-template-894c6c">
    <?php get_template_part('partials/globals/generic-banner'); ?>
    <section class="form-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6 col-lg-5 p-0">
                    <h1><?= get_field('title'); ?></h1>
                    <h3><?= get_field('subtitle'); ?></h3>
                    <div class="form">
                        <?= do_shortcode(get_field('shortcode_form')); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
                    
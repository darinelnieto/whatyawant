   
<?php
/**
 * 
 * Template Name: terminos-condiciones
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
get_header();
?>
<main id="terminos-condiciones-template-a22b43">
    <div class="migas">
        <div class="row justify-content-between">
            <div class="col-12 col-md-3">
                <p class="name-page"><?= the_title(); ?></p>
            </div>
            <div class="col-12 col-md-4">
                <?= mi_breadcrumb(); ?>
            </div>
        </div>
    </div>
    <section>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10">
                    <h1><?= get_field('titulo'); ?></h1>
                    <div class="content">
                        <?= the_content(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
                    
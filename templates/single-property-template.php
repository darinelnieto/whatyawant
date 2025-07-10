   
<?php
/**
 * 
 * Template Name: single-property
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
get_header();
?>
<script>
    $('.header-content-partial-1bbfc7').removeClass('not-fixed');
</script>
<main id="single-property-template-f796d2">
    <?php get_template_part('partials/single-portfolio/single-banner'); ?>
    <?php get_template_part('partials/single-portfolio/portfolio-body'); ?>
    <?php get_template_part('partials/single-portfolio/gallery'); ?>
    <?php get_template_part('partials/globals/map'); ?>
    <?php get_template_part('partials/globals/fqas'); ?>
    <?php get_template_part('partials/single-portfolio/contact'); ?>
    <?php get_template_part('partials/globals/paghinate-posts'); ?>
</main>
<?php get_footer(); ?>
                    
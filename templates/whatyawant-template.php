   
<?php
/**
 * 
 * Template Name: whatyawant
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
get_header();
?>
<main id="whatyawant-template-f532ee">
    <?php get_template_part('partials/globals/generic-banner'); ?>
    <?php get_template_part('partials/whatyawant/wyw-text-content'); ?>
    <?php get_template_part('partials/home/tab-after-banner'); ?>
    <?php get_template_part('partials/globals/parallax'); ?>
    <?php get_template_part('partials/whatyawant/wyw-text-block'); ?>
    <?php get_template_part('partials/home/instagram'); ?>
</main>
<?php get_footer(); ?>

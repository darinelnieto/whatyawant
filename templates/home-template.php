   
<?php
/**
 * 
 * Template Name: home
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
get_header();
?>
<main id="home-template-533cc7">
    <?php get_template_part('partials/home/banner'); ?>
    <?php get_template_part('partials/home/tab-after-banner'); ?>
    <?php get_template_part('partials/home/feature-properties'); ?>
    <?php get_template_part('partials/home/rent-my-property'); ?>
    <?php get_template_part('partials/home/zones-categories'); ?>
    <?php get_template_part('partials/home/video'); ?>
    <?php get_template_part('partials/home/playlist'); ?>
    <?php get_template_part('partials/home/instagram'); ?>
    <?php get_template_part('partials/home/reviews'); ?>
</main>
<?php get_footer(); ?>
                    
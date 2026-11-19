   
<?php
/**
 * 
 * Template Name: blog-page
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
get_header();
?>
<main id="blog-page-template-1bca78">
    <?php get_template_part('partials/globals/generic-banner'); ?>
    <?php get_template_part('partials/globals/blog'); ?>
    <?php get_template_part('partials/home/playlist'); ?>
    <?php get_template_part('partials/home/instagram'); ?>
</main>
<?php get_footer(); ?>
                    
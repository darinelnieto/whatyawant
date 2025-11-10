   
<?php
/**
 * 
 * Template Name: single-blog
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
<main id="single-blog-template-246072">
    <?php get_template_part('partials/single-post/banner-post'); ?>
    <?php get_template_part('partials/single-post/body-post'); ?>
    <?php get_template_part('partials/globals/paghinate-posts'); ?>
</main>
<?php get_footer(); ?>
                    
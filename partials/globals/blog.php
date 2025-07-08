   
<?php
/**
 * 
 * Partial Name: blog
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$blog = new WP_Query(array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'post_per_page' => -1,
    'order' => 'DESC',
));
if($blog->have_posts()):
?>
<section class="blog-partial-7bb291">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-11">
                <div class="row">
                    <?php while($blog->have_posts()): $blog->the_post(); ?>
                        <div class="col-12 col-md-6 mb-5">
                            <a href="<?= get_permalink(); ?>" class="card-post">
                                <div class="image-contain">
                                    <img src="<?= get_the_post_thumbnail_url(); ?>" alt="<?= the_title(); ?>">
                                </div>
                                <div class="text-contain">
                                    <h3><?= the_title(); ?></h3>
                                    <span class="date"><?= get_the_date('j F, Y'); ?></span>
                                    <p class="short-description"><?= get_field('short_description', $blog->ID); ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>             
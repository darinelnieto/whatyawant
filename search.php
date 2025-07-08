<?php
/**
 * 
 * Default search page.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<main id="ditto-search">
	<section>
		<div class="container">
            <p>[Default search page]</p>
            <p>Search: <?= get_search_query() ?></p>
            <p>Results: </p>
            <?php if (have_posts()): ?>
                <ul>
                <?php while ( have_posts() ) : the_post(); ?>
                    <li><a href="<?= get_the_permalink() ?>"><?= get_the_title() ?></a></li>
                <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p class="sorry">Sorry, but nothing matched your search terms. Please try again with some different keywords.</p>
            <?php endif ?>
        </div>
	</section>
</main>

<?php get_footer(); ?>
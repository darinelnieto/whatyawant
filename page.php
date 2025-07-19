<?php
/**
 * 
 * Default page.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<main id="ditto-page">
	<section class="migas">
		<div class="container">
            <div class="row justify-content-between">
                <div class="col-12 col-md-3">
                    <p class="name-page"><?= the_title(); ?></p>
                </div>
                <div class="col-12 col-md-4">
                    <?= mi_breadcrumb(); ?>
                </div>
            </div>
        </div>
	</section>
	<section>
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-12 col-md-11">
					<?= the_content(); ?>
				</div>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
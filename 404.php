<?php
/**
 * 
 * Default 404.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>
<main id="ditto_404_error">
	<section>
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h1>404</h1>
					<h5>Page Not Found</h5>
					<p>This is a custom 404 error page. The page requested couldn't be found: this could be a spelling error or a removed page.</p>
				</div>
			</div>
		</div>
	</section>
</main>
<script>
	$(()=>{
		$('.header-content-partial-1bbfc7').removeClass('not-fixed');
	});
</script>
<?php get_footer(); ?>
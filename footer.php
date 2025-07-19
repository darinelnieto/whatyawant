<?php
/**
 * 
 * Footer template.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$left_content = get_field('left_content', 'option');
$content_center = get_field('content_center', 'option');
$right_content = get_field('right_content', 'option');
$contact = get_field('whatsapp_content', 'option');
$cta_img = $contact['whatsapp_image'];
?>

<footer id="footer-wrapper">
	<?php if($contact['whatsapp_image']): ?>
		<a href="https://wa.me/57<?= $contact['whatsapp_number']; ?>?text=<?php echo $contact['message_whatsapp']; ?>" class="whatsapp-fixed" target="_blanck">
			<img src="<?= $cta_img['url']; ?>" alt="<?= $cta_img['title']; ?>" width="<?= $cta_img['width']; ?>" height="<?= $cta_img['height']; ?>">
		</a>
	<?php endif; ?>
	<div class="scroll-top">
		<a href="#">
			<i class="fa fa-angle-up fa-stack"></i>
		</a>
	</div>
    <div class="top-content">
		<div class="container">
			<div class="row">
				<div class="col-12 col-md-6 col-lg-4 mb-5 mb-md-0">
					<h2><?= $left_content['title']; ?></h2>
					<hr>
					<p class="description"><?= $left_content['description']; ?></p>
				</div>
				<div class="col-12 col-md-6 col-lg-4 mb-5 mb-md-0">
					<h2><?= $content_center['title']; ?></h2>
					<hr>
					<?php if($content_center['contact']): ?>
						<ul class="contact-list">
							<?php foreach($content_center['contact'] as $li): ?>
								<li>
									<a href="<?= $li['cta']['url']; ?>" target="<?= $li['cta']['target']; ?>">
										<?= $li['icon']; ?>
										<?= $li['cta']['title']; ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
						<hr class="contact-hr">
					<?php endif; if($content_center['main_cta']): ?>
						<div class="cta-content">
							<a href="<?= $content_center['main_cta']['url']; ?>" target="<?= $content_center['main_cta']['target']; ?>" class="cta">
								<?= $content_center['main_cta']['title']; ?>
							</a>
						</div>
						<hr class="contact-hr">
					<?php endif; if($content_center['social_networks']): ?>
						<ul class="social-network">
							<?php foreach($content_center['social_networks'] as $li): ?>
								<li>
									<a href="<?= $li['link']['url']; ?>" target="<?= $li['link']['target']; ?>">
										<?= $li['icon']; ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
				<div class="col-12 col-md-4 mb-5 mb-md-0">
					<h2><?= $right_content['title']; ?></h2>
					<hr>
					<?php if($right_content['blogs']): ?>
						<ul class="blog-list">
							<?php foreach($right_content['blogs'] as $item): ?>
								<li>
									<a href="<?= get_permalink($item['blog']->ID); ?>">
										<div class="image-contain">
											<img src="<?= get_the_post_thumbnail_url($item['blog']->ID); ?>" alt="<?= get_the_title($item['blog']->ID); ?>">
										</div>
										<div class="text">
											<h3><?= get_the_title($item['blog']->ID); ?></h3>
											<p><?= get_the_date('j F, Y', $item['blog']->ID); ?></p>
										</div>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="copyright">
		<div class="container">
			<div class="row">
				<div class="col-12 text-center">
					<img src="<?= esc_url( wp_get_attachment_url( get_theme_mod( 'custom_logo' ) ) ); ?>" alt="Logo whatyawant" class="logo">
					<div class="p-copy"><?= get_field('copyright_content', 'option'); ?></div>
				</div>
			</div>
		</div>
	</div>
</footer>

</div>

<?php wp_footer(); ?>
</body>
</html>
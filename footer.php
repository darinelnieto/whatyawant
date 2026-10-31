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
	<?php if(!empty($contact['whatsapp_image'])): ?>
		<a href="https://wa.me/57<?= $contact['whatsapp_number']; ?>?text=<?php echo $contact['message_whatsapp']; ?>" class="whatsapp-fixed" target="_blank" rel="noopener" aria-label="Escríbenos por WhatsApp">
			<?= wp_get_attachment_image($cta_img['ID'] ?? '', 'large', false, array(
				'class' => 'whatsapp-img',
				'loading' => 'lazy',
				'decoding' => 'async'
			)) ?>
		</a>
	<?php endif; ?>
	<div class="scroll-top">
		<a href="#" aria-label="Volver arriba">
			<i class="fa fa-angle-up fa-stack" aria-hidden="true"></i>
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
									<a href="<?= esc_url($li['link']['url']); ?>" target="<?= esc_attr($li['link']['target']); ?>" rel="noopener" aria-label="<?= esc_attr($li['link']['title'] ?: wp_parse_url($li['link']['url'], PHP_URL_HOST)); ?>">
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
											<?= wp_get_attachment_image(get_post_thumbnail_id($item['blog']->ID), 'medium', false, array(
												'class' => 'footer-blog-image',
												'loading' => 'lazy',
												'decoding' => 'async',
												'alt' => get_the_title($item['blog']->ID),
											)) ?>
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
					<?= wp_get_attachment_image(get_theme_mod('custom_logo'), 'full', false, array(
						'class' => 'logo',
						'loading' => 'lazy',
						'decoding' => 'async',
						'alt' => 'Logo whatyawant',
					)) ?>
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
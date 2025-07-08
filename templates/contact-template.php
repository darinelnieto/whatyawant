   
<?php
/**
 * 
 * Template Name: contact
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
get_header();
$contact = get_field('contact_group');
$social_networks = get_field('content_center', 'option');
$form = get_field('form');
?>
<main id="contact-template-0389cb">
    <?php get_template_part('partials/globals/generic-banner'); ?>
    <section class="contact">
        <div class="row">
            <div class="col-12 col-md-6 contact-contain">
                <?php if($contact['title']): ?>
                    <h2><?= $contact['title']; ?></h2>
                    <hr>
                <?php endif; if($contact['list']): ?>
                    <ul class="contact-list">
                        <?php foreach($contact['list'] as $li): ?>
                            <li>
                                <a href="<?= $li['link']['url']; ?>" target="<?= $li['link']['target']; ?>">
                                    <?= $li['icon']; ?> <?= $li['link']['title']; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; if($social_networks['social_networks']): ?>
                    <hr class="gray">
                    <ul class="social-network">
                        <?php foreach($social_networks['social_networks'] as $li): ?>
                            <li>
                                <a href="<?= $li['link']['url']; ?>" target="<?= $li['link']['target']; ?>">
                                    <?= $li['icon']; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="col-12 col-md-6 form-contain">
                <h2><?= $form['title']; ?></h2>
                <hr>
                <div class="form">
                    <?= do_shortcode($form['shortcode']); ?>
                </div>
            </div>
        </div>
    </section>
    <?php get_template_part('partials/home/rent-my-property'); ?>
</main>
<?php get_footer(); ?>
                    
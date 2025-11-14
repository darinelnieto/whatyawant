   
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
$content = get_field('i_want_to_rent_my_property');
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
    <section class="rent-my-property">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <?php if($content['title']): ?>
                        <h2><?= $content['title']; ?></h2>
                    <?php endif; if($content['cta_link']): ?>
                        <a href="<?= $content['cta_link']; ?>" target="_blank">
                            <span><?= $content['cta_text']; ?></span>
                        </a>
                    <?php endif; if($content['description']): ?>
                        <p><?= $content['description']; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
                    
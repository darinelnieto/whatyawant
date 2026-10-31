   
<?php
/**
 * 
 * Partial Name: contact
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$contact = get_field('contact', 'option');
$cta_img = $contact['whatsapp_image'];
?>
<section class="contact-partial-3294a6">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <?php if(!empty($contact['title'])): ?>
                    <h2><?= $contact['title'] ?></h2>
                <?php endif; if(!empty($contact['description'])): ?>
                    <div class="description">
                        <?= $contact['description']; ?>
                    </div>
                <?php endif; if(!empty($contact['whatsapp_image'])): ?>
                    <a href="https://wa.me/57<?= $contact['whatsapp_number']; ?>?text=<?php echo $contact['message_whatsapp']; echo ' '. get_the_title(); ?>" target="_blank" rel="noopener" aria-label="Escríbenos por WhatsApp">
                        <?= wp_get_attachment_image($cta_img['ID'] ?? '', 'large', false, array(
                            'class' => 'whatsapp-img',
                            'loading' => 'lazy',
                            'decoding' => 'async'
                        )) ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
                    
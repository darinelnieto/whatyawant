   
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
                <?php if($contact['title']): ?>
                    <h2><?= $contact['title'] ?></h2>
                <?php endif; if($contact['description']): ?>
                    <div class="description">
                        <?= $contact['description']; ?>
                    </div>
                <?php endif; if($contact['whatsapp_image']): ?>
                    <a href="https://wa.me/57<?= $contact['whatsapp_number']; ?>?text=<?php echo $contact['message_whatsapp']; echo ' '. get_the_title(); ?>" target="_blanck">
                        <img src="<?= $cta_img['url']; ?>" alt="<?= $cta_img['title']; ?>" width="<?= $cta_img['width']; ?>" height="<?= $cta_img['height']; ?>">
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
                    
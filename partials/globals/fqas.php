   
<?php
/**
 * 
 * Partial Name: fqas
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
// Propiedades vacacionales usan sus propias FAQ (misma condición que el banner y portfolio-body)
if (get_field('is_vacant_properties') === true){
    $faq = get_field('vacant_properties_preguntas_frecuentes', 'option');
    $faq_title = get_field('vacant_properties_fqas_title', 'option');
} else {
    $faq = get_field('preguntas_frecuentes', 'option');
    $faq_title = get_field('fqas_title', 'option');
}
if(!empty($faq)): 
?>
<section class="fqas-partial-4947e5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <h2 class="title-faq"><?= $faq_title ?? ''; ?></h2>
                <ul class="frequently-asked-questions-content">
                    <?php if(!empty($faq)){
                        foreach($faq as $index => $item_faq){ ?>
                            <li class="item-faq">
                                <div class="question" role="button" tabindex="0" aria-expanded="false" aria-controls="faq-answer-<?= $index; ?>">
                                    <?php echo $item_faq['pregunta'] ?>
                                    <span class="flecha-acordeon" aria-hidden="true"></span>
                                </div>
                                <div class="answer" id="faq-answer-<?= $index; ?>">
                                    <?php echo $item_faq['respuesta'] ?>
                                </div>
                            </li>
                        <?php }
                    } ?>
                </ul>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
   
<?php
/**
 * 
 * Partial Name: fqas
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$faq = get_field('preguntas_frecuentes', 'option');
if(!empty($faq)): 
?>
<section class="fqas-partial-4947e5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <h2 class="title-faq"><?= get_field('fqas_title', 'option') ?? ''; ?></h2>
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
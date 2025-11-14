   
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
if($faq): 
?>
<section class="fqas-partial-4947e5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <h2 class="title-faq"><?= get_field('fqas_title', 'option'); ?></h2>
                <ul class="frequently-asked-questions-content">
                    <?php if($faq){
                        foreach($faq as $item_faq){ ?>
                            <li class="item-faq">
                                <div class="question">
                                    <?php echo $item_faq['pregunta'] ?>
                                    <span class="flecha-acordeon"></span>
                                </div>
                                <div class="answer">
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
<script>
    var acc = jQuery('.question');
    var i;

    for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            acc.removeClass('active');
            this.classList.toggle("active");
            jQuery('.answer').css({'display':'none'});
            panel.style.display = "block";
        }
    });
    }
</script>
<?php endif; ?>
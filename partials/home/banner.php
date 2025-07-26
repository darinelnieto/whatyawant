   
<?php
/**
 * 
 * Partial Name: banner
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$banner = get_field('slide_banner');
if($banner):
?>
<section class="banner-partial-0d4ebd owl-carousel">
    <?php foreach($banner as $item): ?>
        <div class="item">
            <?php if($item['image']): ?>
                <img src="<?= $item['image']['url']; ?>" alt="<?= $item['image']['title']; ?>" width="<?= $item['image']['width']; ?>" height="<?= $item['image']['height']; ?>" class="banner-image">
            <?php endif; ?>
            <div class="tecxt-contain">
                <?php if($item['title']): ?>
                    <h2><?= $item['title']; ?></h2>
                <?php endif;  if($item['description']): ?>
                    <div class="description">
                        <?= $item['description']; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</section>
<script>
    $('.banner-partial-0d4ebd').owlCarousel({
        autoplay:true,
        autoplayTimeout: 7000,
        loop:true,
        nav:true,
        dots:false,
        navText:[
            `<i class="fa fa-fw fa-angle-left"></i>`,
            `<i class="fa fa-fw fa-angle-right"></i>`
        ],
        items:1,
        responsive:{
            0:{
                dots:true,
                nav:false,
                autoplay:false
            },
            768:{
                dots:false,
                nav:true,
            }
        }
    }).css({'opacity':1});
</script>
<?php endif; ?>          
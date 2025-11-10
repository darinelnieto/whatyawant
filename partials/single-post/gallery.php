   
<?php
/**
 * 
 * Partial Name: gallery
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$enable_gallery = get_field('enable_gallery');
$images = get_field('galeria');
$marca = get_template_directory_uri().'/images/logo-marca-de-agua-grid.png';
$marca_popup = get_template_directory_uri().'/images/logo-marca-de-agua-popup.png';
$path = $marca;
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>
<section class="gallery-partial-3a2065">
    <?php if($enable_gallery): ?>
    <div class="modal-gallery">
        <a href="" class="close-modal">&times;</a>
        <div class="content">
            <?php if($images){
                foreach($images as $img){ 
                    if($img['height'] > $img['width']){ ?>
                        <div class="item-slide content-img-slider-vertical">
                            <img src="<?php echo $img['sizes']["medium_large"] ?>" class="img-modal-slider" alt="">
                        </div>
                    <?php }else{ ?>
                        <div class="item-slide">
                            <img src="<?php echo $img['sizes']["medium_large"] ?>" class="img-modal-slider" alt="">
                        </div>
                    <?php }
                }
            } ?>
        </div>
        <div class="nav-gallery">
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>
    </div>
    <div class="mt-4" id="gallery__grid">
        <?php if($images){ ?>
            <?php $i = 0; 
                if(count($images) > $i){ ?>
                    <?php foreach ($images as $image_url){ ?>
                        <?php $i++; ?>
                        <?php
                            // var_dump($image_url);				
                            $path = $image_url["sizes"]["medium"];
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $data = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            ?>
                        <?php 
                            if($image_url['height'] > $image_url['width']){ ?>
                                <div class="vertical-img-content">
                                    <img class="el_image gallery-item" onclick="openModal();currentSlide(<?php echo $i ?>)" src="<?= $base64; ?>" alt="<?= $image_url['title']; ?>">
                                </div>
                        <?php }else{ ?>
                                <div class="gallery-item-contain">
                                    <img class="el_image gallery-item" onclick="openModal();currentSlide(<?php echo $i ?>)" src="<?= $base64; ?>" alt="<?= $image_url['title']; ?>">
                                </div>
                        <?php } ?>
                    <?php } ?>
            <?php } ?>
        <?php } ?>
    </div>
    <script>
        /*
            * http://cwestblog.com/2017/06/11/javascript-use-canvas-to-watermark-images/
            * https://gist.github.com/westc/800537c79b7342bd2c44731a5587b941
            */

        function watermarkImage(elemImage, text) {
            // Create test image to get proper dimensions of the image.
            let testImage = new Image();
            testImage.onload = function() {
                let h = testImage.height,
                    w = testImage.width,
                    img = new Image();
                // Once the image with the SVG of the watermark is loaded...
                img.onload = function() {
                    // Make canvas with image and watermark
                    let canvas = Object.assign(document.createElement('canvas'), {
                        width: w,
                        height: h
                    });
                    let ctx = canvas.getContext('2d');
                    ctx.drawImage(testImage, 0, 0);
                    ctx.drawImage(img, 100, 75);
                    // If PNG can't be retrieved show the error in the console
                    try {
                        elemImage.src = canvas.toDataURL('image/png');
                    } catch (e) {
                        console.error('Cannot watermark image with text:', {
                            src: elemImage.src,
                            text: text,
                            error: e
                        });
                    }
                };
                // SVG image watermark (HTML of text at bottom right)
                img.src = "<?= $marca; ?>"
            };
            testImage.src = elemImage.src;
        }

        function watermarkImageModal(elemImage, text) {
            // Create test image to get proper dimensions of the image.
            let testImage = new Image();
            testImage.onload = function() {
                let h = testImage.height,
                    w = testImage.width,
                    img = new Image();
                // Once the image with the SVG of the watermark is loaded...
                img.onload = function() {
                    // Make canvas with image and watermark
                    let canvas = Object.assign(document.createElement('canvas'), {
                        width: w,
                        height: h
                    });
                    let ctx = canvas.getContext('2d');
                    ctx.drawImage(testImage, 0, 0);
                    ctx.drawImage(img, 256, 220);
                    // If PNG can't be retrieved show the error in the console
                    try {
                        elemImage.src = canvas.toDataURL('image/png');
                    } catch (e) {
                        console.error('Cannot watermark image with text:', {
                            src: elemImage.src,
                            text: text,
                            error: e
                        });
                    }
                };
                // SVG image watermark (HTML of text at bottom right)
                img.src = "<?= $marca_popup; ?>"
            };
            testImage.src = elemImage.src;
        }

        let elImg = document.querySelectorAll('.el_image');
        let imgModal = document.querySelectorAll('.img-modal-slider');
        for (var i = 0, len = elImg.length; i < len; i++) {
            watermarkImage(elImg[i]);
            watermarkImageModal(imgModal[i]);
        }


        function openModal(){
            jQuery('.modal-gallery').addClass('open-slider');
            jQuery('.menu-container').css({'position':'relative'});
        };
        jQuery('.close-modal').on('click', function(e){
            jQuery('.modal-gallery').removeClass('open-slider');
            e.preventDefault();
        });

        jQuery('.header-content-inner.blocks-animation').append('<a class="btn-view-gallery" onclick="openModal()"><div class="banner-gallery"></div></a>');
        // slider
        var slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }
        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("item-slide");
            var dots = document.getElementsByClassName("demo");
            if (n > slides.length) {slideIndex = 1}
            if (n < 1) {slideIndex = slides.length}
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex-1].style.visibility = "visible";
            slides[slideIndex-1].style.display = "block";
            dots[slideIndex-1].className += " active";
            captionText.innerHTML = dots[slideIndex-1].alt;
        }
        </script>
    <?php endif; ?>
</section>
import $ from 'jquery';

/*
 * Marca de agua con canvas
 * http://cwestblog.com/2017/06/11/javascript-use-canvas-to-watermark-images/
 */
function watermarkImage(elemImage, markSrc, x, y) {
    const apply = () => {
        if (elemImage.dataset.watermarked) return;
        elemImage.dataset.watermarked = '1';

        const source = new Image();
        source.crossOrigin = 'anonymous';
        source.onload = () => {
            const mark = new Image();
            mark.onload = () => {
                const canvas = Object.assign(document.createElement('canvas'), {
                    width: source.naturalWidth,
                    height: source.naturalHeight
                });
                const ctx = canvas.getContext('2d');
                ctx.drawImage(source, 0, 0);
                ctx.drawImage(mark, x, y);
                try {
                    elemImage.src = canvas.toDataURL('image/webp', 0.85);
                } catch (e) {
                    // Imagen de otro origen sin CORS: se deja sin marca
                }
            };
            mark.src = markSrc;
        };
        source.src = elemImage.getAttribute('src');
    };

    // Se respeta el lazy loading: se marca cuando el navegador la necesita
    if (elemImage.complete && elemImage.naturalWidth) {
        apply();
    } else {
        elemImage.addEventListener('load', apply, { once: true });
    }
}

/*========= Galería con modal (propiedades y blog) =========*/
export default function initGallery() {
    const $gallery = $('.js-gallery');
    if (!$gallery.length) return;

    const $modal = $gallery.find('.modal-gallery');
    const slides = $gallery.find('.item-slide').get();
    const markGrid = $gallery.data('watermark-grid');
    const markPopup = $gallery.data('watermark-popup');
    let slideIndex = 1;

    $gallery.find('.el_image').each(function() {
        watermarkImage(this, markGrid, 100, 75);
    });
    $gallery.find('.img-modal-slider').each(function() {
        watermarkImage(this, markPopup, 256, 220);
    });

    const showSlides = (n) => {
        if (!slides.length) return;
        if (n > slides.length) slideIndex = 1;
        if (n < 1) slideIndex = slides.length;
        slides.forEach((slide) => { slide.style.display = 'none'; });
        slides[slideIndex - 1].style.visibility = 'visible';
        slides[slideIndex - 1].style.display = 'block';
    };

    const openModal = () => {
        $modal.addClass('open-slider').attr('aria-hidden', 'false');
        $('.menu-container').css({ position: 'relative' });
        $modal.find('.close-modal').trigger('focus');
    };

    const closeModal = () => {
        $modal.removeClass('open-slider').attr('aria-hidden', 'true');
    };

    showSlides(slideIndex);

    $gallery.on('click', '.gallery-item', function() {
        slideIndex = Number($(this).data('index')) || 1;
        showSlides(slideIndex);
        openModal();
    }).on('keydown', '.gallery-item', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            $(this).trigger('click');
        }
    });

    $modal.on('click', '.prev', (e) => { e.preventDefault(); showSlides(slideIndex -= 1); });
    $modal.on('click', '.next', (e) => { e.preventDefault(); showSlides(slideIndex += 1); });
    $modal.on('click', '.close-modal', (e) => { e.preventDefault(); closeModal(); });

    $(document).on('keydown', (e) => {
        if (!$modal.hasClass('open-slider')) return;
        if (e.key === 'Escape') closeModal();
        if (e.key === 'ArrowLeft') showSlides(slideIndex -= 1);
        if (e.key === 'ArrowRight') showSlides(slideIndex += 1);
    });

    // El banner de la propiedad abre la galería
    $('.single-banner-partial-1c9173').on('click', openModal);

    $('.header-content-inner.blocks-animation').append(
        $('<a href="#" class="btn-view-gallery" role="button" aria-label="Ver galería"><div class="banner-gallery"></div></a>').on('click', (e) => {
            e.preventDefault();
            openModal();
        })
    );
}

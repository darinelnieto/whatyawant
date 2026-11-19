import $ from 'jquery';

// Owl renderiza botones sin nombre accesible; se lo damos tras cada render
function labelControls(event) {
    const $root = $(event.target);
    $root.find('.owl-prev').attr({ 'aria-label': 'Slide anterior', role: 'button' });
    $root.find('.owl-next').attr({ 'aria-label': 'Slide siguiente', role: 'button' });
    $root.find('.owl-dot').each(function(i) {
        $(this).attr('aria-label', `Ir al slide ${i + 1}`);
    });
    $root.find('.owl-item.cloned img').attr({ loading: 'lazy', fetchpriority: 'auto' });
}

/*
 * Marca de agua del banner. A diferencia de la galería:
 * - Se dibuja sobre la resolución que eligió el navegador (currentSrc del srcset), así no se ve borrosa.
 * - Se escala según el tamaño en pantalla (object-fit: cover), así no se ve gigante.
 * - Se centra, porque el cover recorta arriba y abajo.
 */
const MARK_MAX_CSS_WIDTH = 180; // px visibles en pantalla
const MARK_VIEWPORT_RATIO = 0.35; // en móvil, máximo 35% del ancho

function watermarkBanner(img, markSrc) {
    const apply = () => {
        // Un clon de Owl puede copiar el flag sin la imagen ya marcada: se comprueba el src
        if (img.dataset.watermarked && img.src.startsWith('data:')) return;
        img.dataset.watermarked = '1';

        const source = new Image();
        source.crossOrigin = 'anonymous';
        source.onload = () => {
            const mark = new Image();
            mark.onload = () => {
                const natW = source.naturalWidth;
                const natH = source.naturalHeight;
                const boxW = img.clientWidth || img.parentNode.clientWidth || natW;
                const boxH = img.clientHeight || img.parentNode.clientHeight || natH;
                const coverScale = Math.max(boxW / natW, boxH / natH);

                const cssW = Math.min(MARK_MAX_CSS_WIDTH, boxW * MARK_VIEWPORT_RATIO);
                const markW = Math.round(cssW / coverScale);
                const markH = Math.round(markW * mark.naturalHeight / mark.naturalWidth);

                const canvas = Object.assign(document.createElement('canvas'), { width: natW, height: natH });
                const ctx = canvas.getContext('2d');
                ctx.imageSmoothingQuality = 'high';
                ctx.drawImage(source, 0, 0);
                ctx.drawImage(mark, Math.round((natW - markW) / 2), Math.round((natH - markH) / 2), markW, markH);
                try {
                    const data = canvas.toDataURL('image/webp', 0.92);
                    // Sin srcset el navegador ya no cambia la imagen por otra sin marca
                    img.removeAttribute('srcset');
                    img.removeAttribute('sizes');
                    img.src = data;
                } catch (e) {
                    // Imagen de otro origen sin CORS: se deja sin marca
                }
            };
            mark.src = markSrc;
        };
        source.src = img.currentSrc || img.getAttribute('src');
    };

    if (img.complete && img.naturalWidth) {
        apply();
    } else {
        img.addEventListener('load', apply, { once: true });
    }
}

/*========= Banner slide de la propiedad (vacacionales) =========*/
export default function initSingleBanner() {
    const $slider = $('.single-banner-partial-1c9173 .single-banner-slider');
    if (!$slider.length) return;

    const mark = $slider.data('watermark');
    const applyWatermark = () => {
        $slider.find('.js-banner-watermark').each(function() {
            watermarkBanner(this, mark);
        });
    };

    $slider.on('initialized.owl.carousel refreshed.owl.carousel', applyWatermark);

    $slider.owlCarousel({
        autoplay: true,
        autoplayTimeout: 4000,
        autoplayHoverPause: true,
        loop: true,
        nav: true,
        dots: false,
        navText: [
            '<i class="fa fa-fw fa-angle-left" aria-hidden="true"></i>',
            '<i class="fa fa-fw fa-angle-right" aria-hidden="true"></i>'
        ],
        items: 1,
        responsive: {
            0: {
                dots: true,
                nav: false
            },
            768: {
                dots: false,
                nav: true
            }
        },
        onInitialized: labelControls,
        onRefreshed: labelControls
    });

    // Los clones de Owl copian el src original: se marcan también
    applyWatermark();
}

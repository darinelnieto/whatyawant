import $ from 'jquery';

/*========= Slide de imágenes en zonas / categorías =========*/
export default function initZones() {
    $('.zones-categories-partial-6c7844 .zones-slider').each(function() {
        const $slider = $(this);

        $slider.owlCarousel({
            items: 1,
            loop: $slider.children('.item').length > 1,
            autoplay: true,
            autoplayTimeout: 4000,
            nav: false,
            dots: false,
            mouseDrag: false,
            touchDrag: false,
            pullDrag: false,
            freeDrag: false,
            onInitialized: (event) => {
                $(event.target).find('.owl-item.cloned img').attr('loading', 'lazy');
            }
        });
    });
}

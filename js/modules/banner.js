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

/*========= Banner home =========*/
export default function initBanner() {
    const $banner = $('.banner-partial-0d4ebd');
    if (!$banner.length) return;

    $banner.owlCarousel({
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
                nav: false,
                autoplay: true
            },
            768: {
                dots: false,
                nav: true
            }
        },
        onInitialized: labelControls,
        onRefreshed: labelControls
    });
}

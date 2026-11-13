import $ from 'jquery';

const isMobile = () => window.innerWidth < 1000;

/*========= Header, menú y scroll =========*/
export default function initHeader() {
    const $header = $('.header-content-partial-1bbfc7');
    const $scrollTop = $('.scroll-top');

    // Link activo del menú
    const currentUrl = window.location.href.split(/[?#]/)[0];
    $('.menu-contain a').each(function() {
        if (this.href.split(/[?#]/)[0] === currentUrl) {
            $(this).addClass('active').attr('aria-current', 'page');
        }
    });

    // Header sticky y botón "volver arriba" (scroll pasivo + rAF)
    let ticking = false;
    const onScroll = () => {
        const top = window.scrollY;
        $header.toggleClass('sticky', top > 0 || $('.menu-contain').hasClass('active'));
        $scrollTop.toggleClass('active', top > 1000);
        ticking = false;
    };
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(onScroll);
            ticking = true;
        }
    }, { passive: true });

    $('.scroll-top a').on('click', function(e) {
        e.preventDefault();
        window.scrollTo(0, 0);
    });

    if (!isMobile()) return;

    // Submenús en móvil: primer toque abre, segundo navega
    $('.with-sub-menu > ul').hide();
    $('.with-sub-menu > a').on('click', function(e) {
        const $submenu = $(this).next('ul');
        if (!$submenu.is(':visible')) {
            e.preventDefault();
            $submenu.slideDown(300);
        }
    });

    // Menú hamburguesa (accesible por teclado)
    const $bar = $('.bar-menu');
    const toggleMenu = () => {
        const open = !$bar.hasClass('active');
        $bar.toggleClass('active', open).attr('aria-expanded', open);
        $('.menu-contain').toggleClass('active', open);
        $header.toggleClass('sticky', open || window.scrollY > 0);
    };
    $bar.on('click', toggleMenu).on('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggleMenu();
        }
    });

    // Filtros desplegables del listado de propiedades
    $('#filters').on('click', 'h3', function() {
        $(this).parent().toggleClass('active');
    });
}

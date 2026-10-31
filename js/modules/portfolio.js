import $ from 'jquery';

const POSTS_PER_PAGE = 16;
const AVAILABILITY_ICONS = {
    'Disponible': 'fa-face-smile',
    'No Disponible': 'fa-face-frown',
    'Proximamente': 'fa-face-meh'
};

const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (c) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
}[c]));

function cardTemplate(item) {
    const icon = AVAILABILITY_ICONS[item.disponibilidad];
    const availability = escapeHtml(item.disponibilidad) + (icon ? ` <i class="fa-regular ${icon}" aria-hidden="true"></i>` : '');
    const title = escapeHtml(item.title);
    // item.image viene de wp_get_attachment_image() (con srcset); la URL queda como respaldo
    const image = item.image || `<img src="${escapeHtml(item.thumbnail)}" alt="${title}" loading="lazy" decoding="async">`;

    return `
        <div class="col-12 col-md-4 col-lg-3 mb-5">
            <div class="card card-propertie">
                <a href="${escapeHtml(item.permalink)}" target="_blank" rel="noopener" class="image-contain" aria-label="${title}">
                    ${image}
                    <span class="availability">${availability}</span>
                </a>
                <div class="card-body">
                    <a href="${escapeHtml(item.permalink)}" target="_blank" rel="noopener">
                        <h3>${title}</h3>
                    </a>
                    <div class="caracteristic">
                        <p class="p-content-card-product">COP $${escapeHtml(item.valor)}</p>
                        <p class="p-content-card-product">${escapeHtml(item.ubicacion)}</p>
                        <p class="p-content-card-product">Habitaciones: ${escapeHtml(item.habitaciones)}</p>
                    </div>
                </div>
            </div>
        </div>`;
}

function paginationTemplate(totalPages, currentPage) {
    let items = '';
    for (let i = 1; i <= totalPages; i++) {
        const current = i === currentPage;
        items += `<li class="${current ? 'active' : ''}">
            <a href="#" class="page-link" data-page="${i}" aria-label="Página ${i}"${current ? ' aria-current="page"' : ''}>${i}</a>
        </li>`;
    }
    return `<div class="col-12 text-center"><nav class="pagination mt-4" aria-label="Paginación"><ul class="pagination-list">${items}</ul></nav></div>`;
}

const activeFilters = (selector) => $(`${selector} .active`).map(function() {
    return $(this).attr('href');
}).get();

/*========= Listado de propiedades (REST) =========*/
export default function initPortfolio() {
    const $container = $('#contenedor-posts');
    if (!$container.length || !$container.data('endpoint')) return;

    let currentPage = 1;

    const load = () => {
        const availability = activeFilters('.availability');
        const rooms = activeFilters('.rooms');

        $container.attr('aria-busy', 'true');
        $.ajax({
            type: 'GET',
            url: $container.data('endpoint'),
            data: {
                category: $container.data('category') || '',
                availability: availability.length ? availability : '',
                rooms: rooms.length ? rooms : '',
                page: currentPage,
                posts_per_page: POSTS_PER_PAGE
            }
        }).done((resp) => {
            $container.html(resp.portfolio.map(cardTemplate).join('') + paginationTemplate(resp.total_pages, resp.current_page));
        }).fail(() => {
            $container.html('<p>Error cargando propiedades.</p>');
        }).always(() => {
            $container.attr('aria-busy', 'false');
        });
    };

    load();

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const selectedPage = $(this).data('page');
        if (selectedPage === currentPage) return;

        currentPage = selectedPage;
        load();
        const $migas = $('.migas');
        if ($migas.length) {
            $('html, body').animate({ scrollTop: $migas.offset().top }, 100);
        }
    });

    // Resetear página si cambian los filtros
    $('.availability, .rooms').on('click', 'a', function(e) {
        e.preventDefault();
        $(this).toggleClass('active');
        currentPage = 1;
        load();
    });
}

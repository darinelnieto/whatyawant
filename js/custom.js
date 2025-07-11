$(()=>{
    get_portfolio();
});
/*========= Header scroll =========*/
$(window).scroll(function(){
    if($(window).scrollTop() > 0){
        $('.header-content-partial-1bbfc7').addClass('sticky');
    }else{
        $('.header-content-partial-1bbfc7').removeClass('sticky');
    }
});
if($(window).width() < 768){
    var status_menu = false;
    $('.bar-menu').on('click', function(){
        if(status_menu === false){
            $(this).addClass('active');
            $('.menu-contain').addClass('active');
            status_menu = true;
        }else{
            $(this).removeClass('active');
            $('.menu-contain').removeClass('active');
            status_menu = false;
        }
    });
}
/*======= End header scroll =======*/
var availability = [];
var rooms = [];
let currentPage = 1;
const postsPerPage = 2;
function get_portfolio(){
    list_availability();
    list_rooms();

    $.ajax({
        type:'GET',
        url: rout,
        data: {
            category: category,
            availability: availability.length ? availability : '',
            rooms: rooms.length ? rooms : '',
            page: currentPage,
            posts_per_page: postsPerPage
        }
    }).done((resp)=>{
        card_content(resp);
        render_pagination(resp.total_pages, resp.current_page);
    }).fail(()=>{
        $('#contenedor-posts').html('<p>Error cargando propiedades.</p>');
    });
}
/*========= Paginador =========*/
function render_pagination(totalPages, currentPage) {
    let paginationHTML = `<div class="pagination mt-4"><ul class="pagination-list">`;

    for (let i = 1; i <= totalPages; i++) {
        paginationHTML += `<li class="${i === currentPage ? 'active' : ''}">
            <a href="#" class="page-link" data-page="${i}">${i}</a>
        </li>`;
    }

    paginationHTML += `</ul></div>`;

    $('#contenedor-posts').append(`<div class="col-12 text-center">${paginationHTML}</div>`);
}

function list_availability(){
    availability = [];
    var list = $('.availability .active');
    for(var i = 0; i < list.length; i++){
        availability.push($(list[i]).attr('href'));
    }
}
function list_rooms(){
    rooms = [];
    var list = $('.rooms .active');
    for(var i = 0; i < list.length; i++){
        rooms.push($(list[i]).attr('href'));
    }
}
function card_content(resp){
    $('#contenedor-posts').html('');
    for(item of resp.portfolio){
        $('#contenedor-posts').append(`
            <div class="col-12 col-md-4 col-lg-3 mb-5">
                <div class="card card-propertie">
                    <a href="${item.permalink}" class="image-contain">
                        <img src="${item.thumbnail}" alt="${item.title}">
                        <span class="availability">${item.disponibilidad}</span>
                    </a>
                    <div class="card-body">
                        <h3>${item.title}</h3>
                        <div class="caracteristic">
                            <p class="p-content-card-product">COP $${item.valor}</p>
                            <p class="p-content-card-product">${item.ubicacion}</p>
                            <p class="p-content-card-product">Habitaciones: ${item.habitaciones}</p>
                        </div>
                    </div>
                </div>
            </div>    
        `);
    }
}
/*=========== availability ==========*/
$(document).on('click', '.page-link', function(e){
    e.preventDefault();
    const selectedPage = $(this).data('page');
    if (selectedPage !== currentPage) {
        currentPage = selectedPage;
        get_portfolio();
    }
});
/*=============  Resetear página si cambias filtros =============*/
$('.availability').on('click', 'a', function(e){
    $(this).toggleClass('active');
    currentPage = 1;
    get_portfolio();
    e.preventDefault();
});
$('.rooms').on('click', 'a', function(e){
    $(this).toggleClass('active');
    currentPage = 1;
    get_portfolio();
    e.preventDefault();
});
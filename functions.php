<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register Theme Scripts
 * https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
 */
function ditto_scripts() {
  $dist_dir = get_template_directory() . '/dist';
  $dist_uri = get_template_directory_uri() . '/dist';
  $css_ver = file_exists($dist_dir . '/css/main.bundle.css') ? filemtime($dist_dir . '/css/main.bundle.css') : null;
  $js_ver = file_exists($dist_dir . '/js/main.bundle.js') ? filemtime($dist_dir . '/js/main.bundle.js') : null;

  // Un solo CSS y un solo JS (Bootstrap, Owl Carousel, Font Awesome y fuentes vía npm/webpack)
  wp_enqueue_style( 'main-styles', $dist_uri . '/css/main.bundle.css', array(), $css_ver );
  wp_enqueue_script( 'main-scripts', $dist_uri . '/js/main.bundle.js', array( 'jquery' ), $js_ver, array(
    'in_footer' => true,
    'strategy'  => 'defer',
  ) );
}
add_action( 'wp_enqueue_scripts', 'ditto_scripts');

/**
 * jQuery: se usa el de WordPress (sin jquery-migrate) y en el footer
 */
function ditto_jquery_footer() {
  if (is_admin()) {
    return;
  }
  wp_deregister_script('jquery');
  wp_register_script('jquery', false, array('jquery-core'), null, true);
  wp_script_add_data('jquery-core', 'group', 1);
}
add_action( 'wp_enqueue_scripts', 'ditto_jquery_footer', 1 );

/**
 * Quitar assets que el theme no usa (editor clásico, sin bloques)
 */
function ditto_dequeue_unused_assets() {
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');
  wp_dequeue_style('classic-theme-styles');
  wp_dequeue_style('global-styles');
}
add_action( 'wp_enqueue_scripts', 'ditto_dequeue_unused_assets', 100 );

/**
 * ShareThis solo donde está el shortcode (ficha de propiedad y blog) y sin bloquear el render.
 * En el resto de páginas no se carga: evita cookies de terceros y una petición bloqueante.
 */
function ditto_sharethis_only_where_used() {
  if (is_singular(array('portfolio', 'post'))) {
    wp_script_add_data('share-this-share-buttons-mu', 'strategy', 'async');
    return;
  }
  wp_dequeue_script('share-this-share-buttons-mu');
  wp_dequeue_style('share-this-share-buttons-sticky');
}
add_action( 'wp_enqueue_scripts', 'ditto_sharethis_only_where_used', 100 );

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
add_filter('emoji_svg_url', '__return_false');

/**
 * Precarga de la fuente principal (evita saltos de texto / mejora LCP)
 */
function ditto_preload_fonts() {
  $font = '/dist/fonts/raleway-latin-wght-normal.woff2';
  if (file_exists(get_template_directory() . $font)) {
    echo '<link rel="preload" href="' . esc_url(get_template_directory_uri() . $font) . '" as="font" type="font/woff2" crossorigin>' . "\n";
  }
}
add_action( 'wp_head', 'ditto_preload_fonts', 1 );

/**
 * Register Navigation Menus
 * https://developer.wordpress.org/reference/functions/register_nav_menus/
 */
function ditto_navigation_menus() {
  $locations = array(
    'main_menu' => __( 'Main Menu', 'text_domain' )
  );
  register_nav_menus( $locations );
}
add_action( 'init', 'ditto_navigation_menus' );

/**
 * Theme support
 * https://developer.wordpress.org/reference/functions/add_theme_support/
 */
add_theme_support( 'custom-logo' );
add_theme_support( 'title-tag' );
add_theme_support( 'html5', array( 'script', 'style', 'gallery', 'caption' ) );

function ditto_custom_logo_image_attributes( $custom_logo_attr ) {
  // El logo está arriba pero no es el LCP: carga inmediata sin robar prioridad al hero
  $custom_logo_attr['loading'] = 'eager';
  $custom_logo_attr['decoding'] = 'async';
  return $custom_logo_attr;
}
add_filter( 'get_custom_logo_image_attributes', 'ditto_custom_logo_image_attributes' );

/**
 * Login Styles
 */
function ditto_login_styles() { ?>
  <style type="text/css">
    body {
      background-color: #222 !important;
    }
    #login h1 a, .login h1 a {
      display: none;
    }
    #login h1 img {
      width: 100%;
      max-width: 240px;
      max-height: 180px;
    }
  </style>
  <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(event) { 
      let loginImg = document.createElement("img");
        loginImg.src = "<?= get_template_directory_uri() ?>/images/pipe-code-logo.svg";
        loginImg.alt = "WordPress login image";
        document.querySelector('#login h1').appendChild(loginImg);
    });
  </script>
<?php }
add_action( 'login_enqueue_scripts', 'ditto_login_styles' );

/**
 * Disable Gutenberg
 */
add_filter('use_block_editor_for_post', '__return_false', 10);
add_filter('use_block_editor_for_post_type', '__return_false', 10);
// options pages
if (function_exists('acf_add_options_page')){
	acf_add_options_page(array(
		'page_title'    => 'Theme Settings',
		'menu_title'    => 'Theme Settings',
		'menu_slug'     => 'theme-settings',
		'capability'    => 'edit_posts',
		'redirect'      =>  true
	  ));
  acf_add_options_sub_page(array(
		'page_title'     => 'Footer',
		'menu_title'     => 'Footer',
		'parent_slug'   => 'theme-settings',
	));
  acf_add_options_sub_page(array(
		'page_title'     => 'Header',
		'menu_title'     => 'Header',
		'parent_slug'   => 'theme-settings',
	));
  acf_add_options_sub_page(array(
		'page_title'     => 'Portafolio global',
		'menu_title'     => 'Portafolio global',
		'parent_slug'   => 'theme-settings',
	));
  acf_add_options_sub_page(array(
		'page_title'     => 'FAQ',
		'menu_title'     => 'FAQ',
		'parent_slug'   => 'theme-settings',
	));
  acf_add_options_sub_page(array(
		'page_title'     => 'Globals',
		'menu_title'     => 'Globals',
		'parent_slug'   => 'theme-settings',
	));
}
/*========= Caracteristicas =========*/
add_theme_support('post-thumbnails');
add_post_type_support( 'characteristics', 'thumbnail' );
function caracteristicas_post(){
  /*====== Argument post type =====*/
  $args = array(
    'public' => true,
    'has_archive' => true,
    'label'  => 'Caracteristicas',
    'menu_icon' => 'dashicons-editor-ul',
    'supports' => ['title', 'editor', 'thumbnail'],
  );
  /*============ Register post type ============*/
  register_post_type('characteristics', $args);
}
add_action('init', 'caracteristicas_post', 4);
/*================ Portfolio ================*/
add_theme_support('post-thumbnails');
add_post_type_support( 'portfolio', 'thumbnail' );
function portfolio_post(){
  /*====== Argument post type =====*/
  $args = array(
    'public' => true,
    'has_archive' => true,
    'label'  => 'Portfolio',
    'menu_icon' => 'dashicons-schedule',
    'supports' => ['title', 'editor', 'thumbnail'],
  );
  /*============ Register post type ============*/
  register_post_type('portfolio', $args);
   /*============ Argument taxonimy ============*/
   $labels = array(
    'name' => _x('Portfolio Category', 'taxonomy general name'),
    'singular_name' => _x('Portfolio Category', 'taxonomy singular name'),
    'search_items' =>  __('Search Portfolio Category'),
    'all_items' => __('All Portfolio Category'),
    'parent_item' => __('Parent Portfolio Category'),
    'parent_item_colon' => __('Parent Portfolio Category:'),
    'edit_item' => __('Edit Portfolio Category'),
    'update_item' => __('Update Portfolio Category'),
    'add_new_item' => __('Add New Portfolio Category'),
    'new_item_name' => __('New Portfolio Category Name'),
    'menu_name' => __('Portfolio Category'),
  );
  /*========== Register taxonomi ==========*/
  register_taxonomy('portfolio_cat', array('portfolio'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'portfolio_cat'),
  ));
  /*============ Argument taxonimy ============*/
   $labels = array(
    'name' => _x('Disponibilidad', 'taxonomy general name'),
    'singular_name' => _x('Disponibilidad', 'taxonomy singular name'),
    'search_items' =>  __('Search Disponibilidad'),
    'all_items' => __('All Disponibilidad'),
    'parent_item' => __('Parent Disponibilidad'),
    'parent_item_colon' => __('Parent Disponibilidad:'),
    'edit_item' => __('Edit Disponibilidad'),
    'update_item' => __('Update Disponibilidad'),
    'add_new_item' => __('Add New Disponibilidad'),
    'new_item_name' => __('New Disponibilidad Name'),
    'menu_name' => __('Disponibilidad'),
  );
  /*========== Register taxonomi ==========*/
  register_taxonomy('availability_cat', array('portfolio'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'availability_cat'),
  ));
  /*============ Argument taxonimy ============*/
   $labels = array(
    'name' => _x('Numero de habitaciones', 'taxonomy general name'),
    'singular_name' => _x('Numero de habitaciones', 'taxonomy singular name'),
    'search_items' =>  __('Search Numero de habitaciones'),
    'all_items' => __('All Numero de habitaciones'),
    'parent_item' => __('Parent Numero de habitaciones'),
    'parent_item_colon' => __('Parent Numero de habitaciones:'),
    'edit_item' => __('Edit Numero de habitaciones'),
    'update_item' => __('Update Numero de habitaciones'),
    'add_new_item' => __('Add New Numero de habitaciones'),
    'new_item_name' => __('New Numero de habitaciones Name'),
    'menu_name' => __('Numero de habitaciones'),
  );
  /*========== Register taxonomi ==========*/
  register_taxonomy('number_of_rooms', array('portfolio'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'number_of_rooms'),
  ));
}
add_action('init', 'portfolio_post', 3);
/*============== Api portfolio ==============*/
add_action('rest_api_init', function () {
  register_rest_route('relatos', '/list', array(
    array(
      'methods'               => WP_REST_Server::READABLE,
      'callback'              => 'portfolio_list_handler',
      'permission_callback'   => '__return_true',
    )
  ));
});
//
function portfolio_list_handler($request){
  $paged = isset($request['page']) ? intval($request['page']) : 1;
  $per_page = isset($request['posts_per_page']) ? intval($request['posts_per_page']) : 8;

  $query = [
    'post_type' => 'portfolio',
    'post_status' => 'publish',
    'portfolio_cat' => $request['category'],
    'availability_cat' => $request['availability'],
    'number_of_rooms' => $request['rooms'],
    'posts_per_page' => $per_page,
    'paged' => $paged,
    'orderby' => 'title',
    'order' => 'ASC',
  ];

  $news = new WP_Query($query);
  $relatos = array();

  if($news->have_posts()){
    while ($news->have_posts()) {
      $news->the_post();
      array_push($relatos, array(
        'title' => get_the_title(),
        'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'medium_large'),
        // <img> con srcset/sizes generado por WordPress para el card
        'image' => wp_get_attachment_image(get_post_thumbnail_id(), 'medium_large', false, array(
          'class' => 'card-propertie-image',
          'alt' => get_the_title(),
          'sizes' => '(min-width: 992px) 25vw, (min-width: 768px) 33vw, 100vw',
          'loading' => 'lazy',
          'decoding' => 'async',
        )),
        'permalink' => get_permalink(),
        'valor' => get_field('valor'),
        'ubicacion' => get_field('ubicacion'),
        'habitaciones' => get_the_terms(get_the_ID(), 'number_of_rooms')[0]->name ?? '',
        'disponibilidad' => get_the_terms(get_the_ID(), 'availability_cat')[0]->name ?? '',
      ));
    }
    wp_reset_postdata();
  }

  return [
    'portfolio' => $relatos,
    'total_pages' => $news->max_num_pages,
    'current_page' => $paged,
    'total_posts' => $news->found_posts,
  ];
}
/*============ Play list ============*/
function create_playlist() {
   $args = array(
    'public' => true,
    'has_archive' => true,
    'label'  => 'playlist',
    'menu_icon' => 'dashicons-format-audio',
    'supports' => ['title', 'editor', 'thumbnail'],
  );
  /*============ Register post type ============*/
  register_post_type('playlist', $args);
}
add_action( 'init', 'create_playlist' );
/*============= Migas de pan =============*/
function mi_breadcrumb() {
    echo '<nav class="breadcrumb">';
    echo '<a href="' . home_url() . '">Home</a> <span>/</span>';
    
    if (is_category() || is_single()) {
        $category = get_the_category();
        if ($category) {
            echo '<a href="' . get_category_link($category[0]->term_id) . '">' . $category[0]->name . '</a> <span>/</span>';
        }
        if (is_single()) {
            echo '<span>'. get_the_title(). '</span>';
        }
    } elseif (is_page()) {
        global $post;
        if ($post->post_parent) {
            $parent_title = get_the_title($post->post_parent);
            $parent_link = get_permalink($post->post_parent);
            echo '<a href="' . $parent_link . '">' . $parent_title . '</a> <span>/</span>';
        }
        echo '<span>'. get_the_title(). '</span>';
    } elseif (is_search()) {
        echo 'Resultados de búsqueda para: "' . get_search_query() . '"';
    } elseif (is_404()) {
        echo 'Página no encontrada';
    }

    echo '</nav>';
}
/*=========== Svg permission ==========*/
function permitir_svg_seguro($file_types){
    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    return array_merge($file_types, $new_filetypes );
}
add_filter('upload_mimes', 'permitir_svg_seguro');

// Validar SVG de forma segura (opcional, pero recomendado)
function check_for_svg($data, $file, $filename, $mimes) {
    $filetype = wp_check_filetype($filename, $mimes);
    if ($filetype['ext'] === 'svg') {
        $data['ext'] = 'svg';
        $data['type'] = 'image/svg+xml';
    }
    return $data;
}
add_filter('wp_check_filetype_and_ext', 'check_for_svg', 10, 4);

add_filter('wpseo_canonical', function() {
  return get_permalink();
});

add_filter('wpseo_opengraph_url', function() {
  return get_permalink();
});

/*=========== Caché WebP (Apariencia → Imágenes WebP) ==========*/
require_once get_template_directory() . '/inc/webp-cache.php';

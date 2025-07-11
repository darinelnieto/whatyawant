<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register Theme Scripts
 * https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
 */
function ditto_scripts() {
  wp_enqueue_style( 'core', get_template_directory_uri() . '/style.css' );
  wp_enqueue_style( 'main-styles', get_template_directory_uri() . '/css/main.bundle.css' );
  wp_enqueue_style( 'bootstrap.css', get_template_directory_uri() . '/css/bootstrap.min.css' );
  wp_enqueue_style('owl-carousel.css', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css');
  wp_enqueue_style('font-awesome.css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css');

  wp_enqueue_script( 'main-scripts', get_template_directory_uri() . '/js/main.bundle.js', array( 'jquery' ), '', true );
  wp_enqueue_script('font-awesome.js', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js');
  wp_enqueue_script( 'bootstrap.js', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ), '', true );
  wp_enqueue_script('owl-carousel.js', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js');
  wp_register_script('custom.js', get_template_directory_uri() . '/js/custom.js', array('jquery'), '1', true);
  wp_enqueue_script('custom.js');
}
add_action( 'wp_enqueue_scripts', 'ditto_scripts');

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
 * Install latest jQuery version 3.5.1
 */
if (!is_admin()) {
	wp_deregister_script('jquery');
	wp_register_script('jquery', ("https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"), false);
	wp_enqueue_script('jquery');
}

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
        'thumbnail' => get_the_post_thumbnail_url(),
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
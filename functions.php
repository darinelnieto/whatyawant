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

/*=========== WebP tools ==========*/
function ditto_webp_add_tools_page() {
  add_theme_page(
    'WebP Tools',
    'WebP Tools',
    'edit_theme_options',
    'ditto-webp-tools',
    'ditto_webp_render_tools_page'
  );
}
add_action('admin_menu', 'ditto_webp_add_tools_page');

function ditto_webp_render_tools_page() {
  if (!current_user_can('edit_theme_options')) {
    return;
  }

  $uploads = wp_get_upload_dir();
  ?>
  <div class="wrap">
    <h1>WebP Tools</h1>
    <p>Convierte imagenes por bloques (JPG/JPEG/PNG) y omite automaticamente las que ya tienen .webp.</p>
    <table class="form-table" role="presentation">
      <tr>
        <th scope="row"><label for="ditto_webp_quality">Calidad</label></th>
        <td><input id="ditto_webp_quality" type="number" min="1" max="100" value="82" /></td>
      </tr>
      <tr>
        <th scope="row"><label for="ditto_webp_batch">Tamano de bloque</label></th>
        <td><input id="ditto_webp_batch" type="number" min="1" max="1000" value="250" /></td>
      </tr>
      <tr>
        <th scope="row">Ruta uploads</th>
        <td><code><?php echo esc_html($uploads['basedir']); ?></code></td>
      </tr>
    </table>

    <p>
      <button class="button button-primary" id="ditto_webp_start">Convertir todo</button>
      <button class="button" id="ditto_webp_one">Convertir 1 bloque</button>
      <button class="button" id="ditto_webp_reset">Revertir WebP (borrar por bloques)</button>
      <button class="button" id="ditto_webp_stop">Detener</button>
    </p>

    <pre id="ditto_webp_log" style="background:#fff; border:1px solid #ccd0d4; padding:12px; max-height:360px; overflow:auto;"></pre>
  </div>

  <script>
    (function($){
      var running = false;
      var nonce = '<?php echo esc_js(wp_create_nonce('ditto_webp_tools')); ?>';

      function log(msg) {
        var el = $('#ditto_webp_log');
        el.append(msg + "\n");
        el.scrollTop(el[0].scrollHeight);
      }

      function runBatch(keepRunning) {
        if (!running && keepRunning) return;

        var quality = parseInt($('#ditto_webp_quality').val(), 10) || 82;
        var batch = parseInt($('#ditto_webp_batch').val(), 10) || 250;

        $.post(ajaxurl, {
          action: 'ditto_webp_convert_batch',
          nonce: nonce,
          quality: quality,
          batch_size: batch
        }).done(function(resp){
          if (!resp || !resp.success) {
            log('Error: ' + ((resp && resp.data && resp.data.message) ? resp.data.message : 'No response'));
            running = false;
            return;
          }

          var data = resp.data;
          log('Convertidas: ' + data.converted + ' | Omitidas: ' + data.skipped + ' | Errores: ' + data.errors + ' | Pendientes: ' + data.remaining);

          if (data.engine_counts) {
            log('Motores: wp_editor=' + (data.engine_counts.wp_editor || 0) + ' | cwebp=' + (data.engine_counts.cwebp || 0) + ' | gd=' + (data.engine_counts.gd || 0));
          }

          if (data.error_reasons) {
            Object.keys(data.error_reasons).forEach(function(reason){
              log('Error [' + reason + ']: ' + data.error_reasons[reason]);
            });
          }

          if (data.error_samples && data.error_samples.length) {
            data.error_samples.forEach(function(item){
              log('Fallo: ' + item.file + ' -> ' + item.reason);
            });
          }

          if (keepRunning && running && data.remaining > 0) {
            runBatch(true);
          } else if (data.remaining <= 0) {
            running = false;
            log('Proceso terminado.');
          }
        }).fail(function(){
          log('Error de conexion con AJAX.');
          running = false;
        });
      }

      function runResetBatch(keepRunning) {
        if (!running && keepRunning) return;

        var batch = parseInt($('#ditto_webp_batch').val(), 10) || 250;

        $.post(ajaxurl, {
          action: 'ditto_webp_cleanup_batch',
          nonce: nonce,
          batch_size: batch
        }).done(function(resp){
          if (!resp || !resp.success) {
            log('Error reset: ' + ((resp && resp.data && resp.data.message) ? resp.data.message : 'No response'));
            running = false;
            return;
          }

          var data = resp.data;
          log('Reset WebP -> Borradas: ' + data.deleted + ' | Errores: ' + data.errors + ' | Pendientes: ' + data.remaining);

          if (data.error_samples && data.error_samples.length) {
            data.error_samples.forEach(function(item){
              log('No se pudo borrar: ' + item.file);
            });
          }

          if (keepRunning && running && data.remaining > 0) {
            runResetBatch(true);
          } else if (data.remaining <= 0) {
            running = false;
            log('Reset terminado. Ya puedes volver a convertir.');
          }
        }).fail(function(){
          log('Error de conexion con AJAX (reset).');
          running = false;
        });
      }

      $('#ditto_webp_start').on('click', function(){
        if (running) return;
        running = true;
        log('Iniciando conversion completa...');
        runBatch(true);
      });

      $('#ditto_webp_one').on('click', function(){
        log('Procesando 1 bloque...');
        runBatch(false);
      });

      $('#ditto_webp_reset').on('click', function(){
        if (running) return;
        running = true;
        log('Iniciando reset de WebP por bloques...');
        runResetBatch(true);
      });

      $('#ditto_webp_stop').on('click', function(){
        running = false;
        log('Proceso detenido por el usuario.');
      });
    })(jQuery);
  </script>
  <?php
}

function ditto_webp_is_valid_file($path) {
  return is_string($path) && $path !== '' && is_file($path) && filesize($path) > 0;
}

function ditto_webp_get_existing_webp_files($limit = 0) {
  $uploads = wp_get_upload_dir();
  $base = isset($uploads['basedir']) ? $uploads['basedir'] : '';
  $results = array();

  if ($base === '' || !is_dir($base)) {
    return $results;
  }

  $it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS)
  );

  foreach ($it as $file) {
    if (!$file->isFile()) {
      continue;
    }

    $path = $file->getPathname();
    if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== 'webp') {
      continue;
    }

    $jpgSource = preg_replace('/\.webp$/i', '.jpg', $path);
    $jpegSource = preg_replace('/\.webp$/i', '.jpeg', $path);
    $pngSource = preg_replace('/\.webp$/i', '.png', $path);

    if (!is_file($jpgSource) && !is_file($jpegSource) && !is_file($pngSource)) {
      continue;
    }

    $results[] = $path;

    if ($limit > 0 && count($results) >= $limit) {
      break;
    }
  }

  return $results;
}

function ditto_webp_get_pending_files($limit = 0) {
  $uploads = wp_get_upload_dir();
  $base = isset($uploads['basedir']) ? $uploads['basedir'] : '';
  $results = array();

  if ($base === '' || !is_dir($base)) {
    return $results;
  }

  $it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS)
  );

  foreach ($it as $file) {
    if (!$file->isFile()) {
      continue;
    }

    $path = $file->getPathname();
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

    if (!in_array($ext, array('jpg', 'jpeg', 'png'), true)) {
      continue;
    }

    $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $path);
    if (ditto_webp_is_valid_file($webpPath)) {
      continue;
    }

    $results[] = $path;

    if ($limit > 0 && count($results) >= $limit) {
      break;
    }
  }

  return $results;
}

function ditto_webp_convert_file($source, $quality = 82, &$meta = null) {
  $meta = array(
    'ok' => false,
    'engine' => 'none',
    'reason' => 'unknown',
  );

  if (!is_string($source) || $source === '' || !is_file($source) || !is_readable($source)) {
    $meta['reason'] = 'source_unreadable';
    return false;
  }

  $quality = (int) $quality;
  if ($quality < 1 || $quality > 100) {
    $quality = 82;
  }

  $target = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $source);
  if (!$target) {
    $meta['reason'] = 'target_path_invalid';
    return false;
  }

  if (is_file($target)) {
    $meta['ok'] = true;
    $meta['engine'] = 'existing';
    $meta['reason'] = 'already_exists';
    return true;
  }

  if (function_exists('wp_get_image_editor')) {
    $editor = wp_get_image_editor($source);

    if (!is_wp_error($editor)) {
      if (method_exists($editor, 'set_quality')) {
        $editor->set_quality($quality);
      }

      $saved = $editor->save($target, 'image/webp');
      if (!is_wp_error($saved) && is_file($target) && filesize($target) > 0) {
        $meta['ok'] = true;
        $meta['engine'] = 'wp_editor';
        $meta['reason'] = 'ok';
        return true;
      }
    }
  }

  $execEnabled = function_exists('exec');
  if ($execEnabled) {
    $disabledFunctions = (string) ini_get('disable_functions');
    if ($disabledFunctions !== '') {
      $disabled = array_map('trim', explode(',', strtolower($disabledFunctions)));
      if (in_array('exec', $disabled, true)) {
        $execEnabled = false;
      }
    }
  }

  if ($execEnabled) {
    exec('command -v cwebp >/dev/null 2>&1', $noop, $hasCwebp);
    if ($hasCwebp === 0) {
      $cmd = sprintf(
        'cwebp -quiet -q %d %s -o %s 2>/dev/null',
        $quality,
        escapeshellarg($source),
        escapeshellarg($target)
      );
      exec($cmd, $out, $status);

      if ($status === 0 && is_file($target) && filesize($target) > 0) {
        $meta['ok'] = true;
        $meta['engine'] = 'cwebp';
        $meta['reason'] = 'ok';
        return true;
      }
    }
  }

  if (!function_exists('imagewebp')) {
    $meta['reason'] = 'imagewebp_unavailable';
    return false;
  }

  $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
  $img = null;

  if (($ext === 'jpg' || $ext === 'jpeg') && function_exists('imagecreatefromjpeg')) {
    $img = @imagecreatefromjpeg($source);
  } elseif ($ext === 'png' && function_exists('imagecreatefrompng')) {
    $img = @imagecreatefrompng($source);
  }

  if (!$img) {
    $meta['reason'] = 'source_decode_failed';
    return false;
  }

  if ($ext === 'png') {
    if (function_exists('imagepalettetotruecolor')) {
      @imagepalettetotruecolor($img);
    }
    imagealphablending($img, false);
    imagesavealpha($img, true);
  }

  $ok = imagewebp($img, $target, $quality);
  imagedestroy($img);

  $written = (bool) $ok && is_file($target) && filesize($target) > 0;
  if ($written) {
    $meta['ok'] = true;
    $meta['engine'] = 'gd';
    $meta['reason'] = 'ok';
    return true;
  }

  $meta['reason'] = 'gd_write_failed';
  return false;
}

function ditto_webp_convert_batch_ajax() {
  if (!current_user_can('edit_theme_options')) {
    wp_send_json_error(array('message' => 'No autorizado.'), 403);
  }

  check_ajax_referer('ditto_webp_tools', 'nonce');

  $batchSize = isset($_POST['batch_size']) ? (int) $_POST['batch_size'] : 250;
  $quality = isset($_POST['quality']) ? (int) $_POST['quality'] : 82;

  if ($batchSize < 1) {
    $batchSize = 1;
  }
  if ($batchSize > 1000) {
    $batchSize = 1000;
  }
  if ($quality < 1 || $quality > 100) {
    $quality = 82;
  }

  $files = ditto_webp_get_pending_files($batchSize);
  $converted = 0;
  $skipped = 0;
  $errors = 0;
  $engineCounts = array(
    'wp_editor' => 0,
    'cwebp' => 0,
    'gd' => 0,
  );
  $errorReasons = array();
  $errorSamples = array();

  foreach ($files as $source) {
    $target = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $source);

    if ($target && ditto_webp_is_valid_file($target)) {
      $skipped++;
      continue;
    }

    if ($target && is_file($target) && !ditto_webp_is_valid_file($target)) {
      @unlink($target);
    }

    $meta = null;
    if (ditto_webp_convert_file($source, $quality, $meta)) {
      $converted++;
      if (is_array($meta) && !empty($meta['engine']) && isset($engineCounts[$meta['engine']])) {
        $engineCounts[$meta['engine']]++;
      }
    } else {
      $errors++;
      $reason = 'conversion_failed';
      if (is_array($meta) && !empty($meta['reason'])) {
        $reason = (string) $meta['reason'];
      }

      if (!isset($errorReasons[$reason])) {
        $errorReasons[$reason] = 0;
      }
      $errorReasons[$reason]++;

      if (count($errorSamples) < 5) {
        $errorSamples[] = array(
          'file' => wp_basename($source),
          'reason' => $reason,
        );
      }
    }
  }

  $remaining = count(ditto_webp_get_pending_files(0));

  wp_send_json_success(array(
    'converted' => $converted,
    'skipped' => $skipped,
    'errors' => $errors,
    'remaining' => $remaining,
    'engine_counts' => $engineCounts,
    'error_reasons' => $errorReasons,
    'error_samples' => $errorSamples,
  ));
}
add_action('wp_ajax_ditto_webp_convert_batch', 'ditto_webp_convert_batch_ajax');

function ditto_webp_cleanup_batch_ajax() {
  if (!current_user_can('edit_theme_options')) {
    wp_send_json_error(array('message' => 'No autorizado.'), 403);
  }

  check_ajax_referer('ditto_webp_tools', 'nonce');

  $batchSize = isset($_POST['batch_size']) ? (int) $_POST['batch_size'] : 250;
  if ($batchSize < 1) {
    $batchSize = 1;
  }
  if ($batchSize > 1000) {
    $batchSize = 1000;
  }

  $files = ditto_webp_get_existing_webp_files($batchSize);
  $deleted = 0;
  $errors = 0;
  $errorSamples = array();

  foreach ($files as $path) {
    if (@unlink($path)) {
      $deleted++;
      continue;
    }

    $errors++;
    if (count($errorSamples) < 5) {
      $errorSamples[] = array(
        'file' => wp_basename($path),
      );
    }
  }

  $remaining = count(ditto_webp_get_existing_webp_files(0));

  wp_send_json_success(array(
    'deleted' => $deleted,
    'errors' => $errors,
    'remaining' => $remaining,
    'error_samples' => $errorSamples,
  ));
}
add_action('wp_ajax_ditto_webp_cleanup_batch', 'ditto_webp_cleanup_batch_ajax');

function ditto_webp_replace_url_if_exists($url) {
  if (!is_string($url) || $url === '' || strpos($url, '.webp') !== false) {
    return $url;
  }

  $uploads = wp_get_upload_dir();
  $baseurl = isset($uploads['baseurl']) ? $uploads['baseurl'] : '';
  $basedir = isset($uploads['basedir']) ? $uploads['basedir'] : '';

  if ($baseurl === '' || $basedir === '' || strpos($url, $baseurl) !== 0) {
    return $url;
  }

  if (!preg_match('/\.(jpe?g|png)$/i', $url)) {
    return $url;
  }

  $relative = substr($url, strlen($baseurl));
  $sourcePath = $basedir . $relative;
  $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $sourcePath);

  if ($webpPath && ditto_webp_is_valid_file($webpPath)) {
    return preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $url);
  }

  return $url;
}

function ditto_webp_filter_attachment_url($url) {
  if (is_admin()) {
    return $url;
  }
  return ditto_webp_replace_url_if_exists($url);
}
add_filter('wp_get_attachment_url', 'ditto_webp_filter_attachment_url', 20);

function ditto_webp_filter_attachment_image_src($image) {
  if (is_admin() || !is_array($image) || empty($image[0])) {
    return $image;
  }
  $image[0] = ditto_webp_replace_url_if_exists($image[0]);
  return $image;
}
add_filter('wp_get_attachment_image_src', 'ditto_webp_filter_attachment_image_src', 20);

function ditto_webp_filter_srcset($sources) {
  if (is_admin() || !is_array($sources)) {
    return $sources;
  }

  foreach ($sources as $w => $item) {
    if (!empty($item['url'])) {
      $sources[$w]['url'] = ditto_webp_replace_url_if_exists($item['url']);
    }
  }

  return $sources;
}
add_filter('wp_calculate_image_srcset', 'ditto_webp_filter_srcset', 20);

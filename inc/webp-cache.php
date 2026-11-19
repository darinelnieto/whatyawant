<?php
/**
 * Caché WebP de la librería de medios.
 *
 * - Nunca escribe ni borra nada dentro de /uploads: solo lo lee.
 * - Las copias WebP viven en /wp-content/webp-cache/ con la misma ruta
 *   y el nombre completo del original (2021/06/foto.jpg -> 2021/06/foto.jpg.webp),
 *   así foto.jpg y foto.png nunca comparten archivo.
 * - Cada WebP se escribe en un temporal, se valida (formato y dimensiones)
 *   y solo entonces se mueve a su nombre final.
 * - Si el WebP pesa más que el original se descarta y se sigue sirviendo el original.
 * - Revertir = borrar la carpeta de caché; los originales quedan intactos.
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

define('DITTO_WEBP_DIR', WP_CONTENT_DIR . '/webp-cache');
define('DITTO_WEBP_URL', content_url('webp-cache'));
define('DITTO_WEBP_STATE', 'ditto_webp_state');
define('DITTO_WEBP_ENABLED', 'ditto_webp_enabled');
define('DITTO_WEBP_FAILED', 'ditto_webp_failed');
define('DITTO_WEBP_TIME_BUDGET', 20); // segundos por petición AJAX

/*========= Rutas =========*/

function ditto_webp_uploads() {
  static $uploads = null;
  if ($uploads === null) {
    $uploads = wp_get_upload_dir();
  }
  return $uploads;
}

/** Ruta relativa a /uploads de un archivo o URL de uploads, o false. */
function ditto_webp_relative($path_or_url, $is_url = false) {
  $uploads = ditto_webp_uploads();
  $base = $is_url ? $uploads['baseurl'] : $uploads['basedir'];
  if ($is_url) {
    // Acepta http/https indistintamente
    $path_or_url = preg_replace('#^https?:#', '', $path_or_url);
    $base = preg_replace('#^https?:#', '', $base);
  }
  if (strpos($path_or_url, $base . '/') !== 0) {
    return false;
  }
  $relative = substr($path_or_url, strlen($base) + 1);
  if (!preg_match('/\.(jpe?g|png)$/i', $relative) || strpos($relative, '..') !== false) {
    return false;
  }
  return $relative;
}

function ditto_webp_target($relative) {
  return DITTO_WEBP_DIR . '/' . $relative . '.webp';
}

function ditto_webp_skip_marker($relative) {
  return ditto_webp_target($relative) . '.skip';
}

/*========= Conversión segura de un archivo =========*/

/**
 * @return string 'converted' | 'exists' | 'larger' | 'failed:<motivo>'
 */
function ditto_webp_convert($relative, $quality = 82) {
  $source = ditto_webp_uploads()['basedir'] . '/' . $relative;
  $target = ditto_webp_target($relative);

  if (!is_file($source) || !is_readable($source)) {
    return 'failed:no_legible';
  }
  if (is_file($target) && filemtime($target) >= filemtime($source)) {
    return 'exists';
  }
  if (is_file(ditto_webp_skip_marker($relative))) {
    return 'larger';
  }

  $source_size = @getimagesize($source);
  if (!$source_size) {
    return 'failed:no_es_imagen';
  }

  if (!wp_mkdir_p(dirname($target))) {
    return 'failed:sin_permiso_escritura';
  }

  wp_raise_memory_limit('image');
  $editor = wp_get_image_editor($source);
  if (is_wp_error($editor)) {
    return 'failed:' . $editor->get_error_code();
  }
  $editor->set_quality($quality);

  $tmp = $target . '.' . wp_generate_password(6, false) . '.tmp.webp';
  $saved = $editor->save($tmp, 'image/webp');
  if (is_wp_error($saved)) {
    @unlink($tmp);
    return 'failed:' . $saved->get_error_code();
  }
  $tmp = $saved['path'];

  // Validación: tiene que ser un WebP real con las mismas dimensiones del original
  $check = @getimagesize($tmp);
  $valid = $check
    && $check['mime'] === 'image/webp'
    && (int) $check[0] === (int) $source_size[0]
    && (int) $check[1] === (int) $source_size[1]
    && filesize($tmp) > 0;

  if (!$valid) {
    @unlink($tmp);
    return 'failed:validacion';
  }

  // Si no ahorra peso, se queda el original
  if (filesize($tmp) >= filesize($source)) {
    @unlink($tmp);
    @touch(ditto_webp_skip_marker($relative));
    return 'larger';
  }

  if (!@rename($tmp, $target)) {
    @unlink($tmp);
    return 'failed:rename';
  }

  return 'converted';
}

/*========= Recorrido por carpetas (reanudable) =========*/

/** Carpetas de la librería de medios (raíz + año/mes); excluye cachés de plugins. */
function ditto_webp_media_dirs() {
  $base = ditto_webp_uploads()['basedir'];
  $dirs = array('');

  if (get_option('uploads_use_yearmonth_folders')) {
    foreach ((array) glob($base . '/[0-9][0-9][0-9][0-9]/[0-1][0-9]', GLOB_ONLYDIR) as $dir) {
      $dirs[] = substr($dir, strlen($base) + 1);
    }
  } else {
    $it = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS),
      RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($it as $item) {
      if ($item->isDir()) {
        $dirs[] = substr($item->getPathname(), strlen($base) + 1);
      }
    }
  }

  sort($dirs);
  return $dirs;
}

function ditto_webp_dir_images($dir) {
  $base = ditto_webp_uploads()['basedir'];
  $path = $dir === '' ? $base : $base . '/' . $dir;
  $files = array();

  foreach ((array) @scandir($path) as $name) {
    if (preg_match('/\.(jpe?g|png)$/i', $name) && is_file($path . '/' . $name)) {
      $files[] = $dir === '' ? $name : $dir . '/' . $name;
    }
  }

  sort($files);
  return $files;
}

function ditto_webp_default_state() {
  return array(
    'dirs' => array(),
    'dir' => 0,
    'offset' => 0,
    'current' => '',
    'converted' => 0,
    'existing' => 0,
    'larger' => 0,
    'failed' => 0,
    'done' => false,
  );
}

function ditto_webp_log_failure($relative, $reason) {
  $failed = get_option(DITTO_WEBP_FAILED, array());
  if (count($failed) < 500) {
    $failed[$relative] = $reason;
    update_option(DITTO_WEBP_FAILED, $failed, false);
  }
}

function ditto_webp_ajax_guard() {
  if (!current_user_can('edit_theme_options')) {
    wp_send_json_error(array('message' => 'No autorizado.'), 403);
  }
  check_ajax_referer('ditto_webp_tools', 'nonce');
}

function ditto_webp_flush_page_cache() {
  if (function_exists('wpo_cache_flush')) {
    wpo_cache_flush();
  }
}

function ditto_webp_process_ajax() {
  ditto_webp_ajax_guard();
  @set_time_limit(60);

  $quality = isset($_POST['quality']) ? max(40, min(100, (int) $_POST['quality'])) : 82;
  $state = get_option(DITTO_WEBP_STATE);

  if (!empty($_POST['restart']) || !is_array($state)) {
    $state = ditto_webp_default_state();
    $state['dirs'] = ditto_webp_media_dirs();
    delete_option(DITTO_WEBP_FAILED);
  }

  // Si la petición anterior murió a mitad de un archivo (memoria/tiempo), se marca y se salta
  if ($state['current'] !== '') {
    ditto_webp_log_failure($state['current'], 'interrumpido (imagen demasiado grande)');
    $state['failed']++;
    $state['offset']++;
    $state['current'] = '';
  }

  $start = microtime(true);

  while (!$state['done'] && (microtime(true) - $start) < DITTO_WEBP_TIME_BUDGET) {
    if ($state['dir'] >= count($state['dirs'])) {
      $state['done'] = true;
      break;
    }

    $files = ditto_webp_dir_images($state['dirs'][$state['dir']]);

    while ($state['offset'] < count($files) && (microtime(true) - $start) < DITTO_WEBP_TIME_BUDGET) {
      $relative = $files[$state['offset']];

      $state['current'] = $relative;
      update_option(DITTO_WEBP_STATE, $state, false);

      $result = ditto_webp_convert($relative, $quality);

      if ($result === 'converted') {
        $state['converted']++;
      } elseif ($result === 'exists') {
        $state['existing']++;
      } elseif ($result === 'larger') {
        $state['larger']++;
      } else {
        $state['failed']++;
        ditto_webp_log_failure($relative, substr($result, 7));
      }

      $state['current'] = '';
      $state['offset']++;
    }

    if ($state['offset'] >= count($files)) {
      $state['dir']++;
      $state['offset'] = 0;
    }
  }

  update_option(DITTO_WEBP_STATE, $state, false);

  if ($state['done']) {
    ditto_webp_flush_page_cache();
  }

  wp_send_json_success(ditto_webp_public_state($state));
}
add_action('wp_ajax_ditto_webp_process', 'ditto_webp_process_ajax');

function ditto_webp_public_state($state) {
  $total = count($state['dirs']);
  return array(
    'converted' => $state['converted'],
    'existing' => $state['existing'],
    'larger' => $state['larger'],
    'failed' => $state['failed'],
    'done' => $state['done'],
    'dirs_done' => min($state['dir'], $total),
    'dirs_total' => $total,
    'current_dir' => $state['dirs'][$state['dir']] ?? '',
    'failures' => array_slice(get_option(DITTO_WEBP_FAILED, array()), -10, 10, true),
  );
}

/*========= Revertir: borra solo dentro de /webp-cache =========*/

function ditto_webp_clear_ajax() {
  ditto_webp_ajax_guard();
  @set_time_limit(60);

  $root = realpath(DITTO_WEBP_DIR);
  $deleted = 0;
  $start = microtime(true);
  $remaining = false;

  if ($root && is_dir($root)) {
    $it = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
      RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($it as $item) {
      if ((microtime(true) - $start) > DITTO_WEBP_TIME_BUDGET) {
        $remaining = true;
        break;
      }
      $path = $item->getPathname();
      // Defensa extra: jamás tocar algo fuera de la carpeta de caché
      if (strpos($path, $root . DIRECTORY_SEPARATOR) !== 0) {
        continue;
      }
      if ($item->isDir()) {
        @rmdir($path);
      } elseif (@unlink($path)) {
        $deleted++;
      }
    }
  }

  if (!$remaining) {
    delete_option(DITTO_WEBP_STATE);
    delete_option(DITTO_WEBP_FAILED);
    ditto_webp_flush_page_cache();
  }

  wp_send_json_success(array('deleted' => $deleted, 'remaining' => $remaining));
}
add_action('wp_ajax_ditto_webp_clear', 'ditto_webp_clear_ajax');

function ditto_webp_toggle_ajax() {
  ditto_webp_ajax_guard();
  update_option(DITTO_WEBP_ENABLED, empty($_POST['enabled']) ? '0' : '1');
  ditto_webp_flush_page_cache();
  wp_send_json_success();
}
add_action('wp_ajax_ditto_webp_toggle', 'ditto_webp_toggle_ajax');

/*========= Imágenes nuevas y borradas =========*/

function ditto_webp_attachment_files($attachment_id, $metadata) {
  if (empty($metadata['file'])) {
    return array();
  }
  $files = array($metadata['file']);
  $dir = dirname($metadata['file']);
  $dir = $dir === '.' ? '' : $dir . '/';

  if (!empty($metadata['original_image'])) {
    $files[] = $dir . $metadata['original_image'];
  }
  foreach ((array) ($metadata['sizes'] ?? array()) as $size) {
    if (!empty($size['file'])) {
      $files[] = $dir . $size['file'];
    }
  }
  return array_filter(array_unique($files), function($file) {
    return preg_match('/\.(jpe?g|png)$/i', $file);
  });
}

// Al subir una imagen se genera su WebP (original y todos sus tamaños)
function ditto_webp_on_upload($metadata, $attachment_id) {
  if (get_option(DITTO_WEBP_ENABLED, '1') === '1' && wp_image_editor_supports(array('mime_type' => 'image/webp'))) {
    foreach (ditto_webp_attachment_files($attachment_id, $metadata) as $relative) {
      ditto_webp_convert($relative);
    }
  }
  return $metadata;
}
add_filter('wp_generate_attachment_metadata', 'ditto_webp_on_upload', 20, 2);

// Al borrar una imagen de la librería se borra su caché WebP
function ditto_webp_on_delete($attachment_id) {
  $metadata = wp_get_attachment_metadata($attachment_id);
  foreach (ditto_webp_attachment_files($attachment_id, $metadata) as $relative) {
    @unlink(ditto_webp_target($relative));
    @unlink(ditto_webp_skip_marker($relative));
  }
}
add_action('delete_attachment', 'ditto_webp_on_delete');

/*========= Servir WebP en el front =========*/

function ditto_webp_serving() {
  return !is_admin() && get_option(DITTO_WEBP_ENABLED, '1') === '1';
}

function ditto_webp_url($url) {
  static $memo = array();

  if (!is_string($url) || $url === '' || !ditto_webp_serving()) {
    return $url;
  }
  if (isset($memo[$url])) {
    return $memo[$url];
  }

  $original = $url;
  // image_downsize() arma la URL de cada tamaño cambiando el nombre de archivo sobre
  // wp_get_attachment_url(), que ya apunta a /webp-cache (foto.jpeg.webp). Resultado:
  // /webp-cache/…/foto-300x225.jpeg, que no existe. Se devuelve a /uploads y se resuelve normal.
  $cache_base = preg_replace('#^https?:#', '', DITTO_WEBP_URL) . '/';
  $no_scheme = preg_replace('#^https?:#', '', $url);
  if (strpos($no_scheme, $cache_base) === 0 && !preg_match('/\.webp$/i', $url)) {
    $url = ditto_webp_uploads()['baseurl'] . '/' . substr($no_scheme, strlen($cache_base));
  }

  $relative = ditto_webp_relative($url, true);
  $memo[$original] = ($relative && is_file(ditto_webp_target($relative)))
    ? DITTO_WEBP_URL . '/' . str_replace('%2F', '/', rawurlencode($relative)) . '.webp'
    : $url;

  return $memo[$original];
}

add_filter('wp_get_attachment_url', 'ditto_webp_url', 20);

add_filter('wp_get_attachment_image_src', function($image) {
  if (is_array($image) && !empty($image[0])) {
    $image[0] = ditto_webp_url($image[0]);
  }
  return $image;
}, 20);

add_filter('wp_calculate_image_srcset', function($sources) {
  if (is_array($sources)) {
    foreach ($sources as $w => $item) {
      $sources[$w]['url'] = ditto_webp_url($item['url']);
    }
  }
  return $sources;
}, 20);

// Imágenes dentro del contenido de posts/páginas
add_filter('wp_content_img_tag', function($html) {
  return preg_replace_callback('/\ssrc="([^"]+)"/', function($m) {
    return ' src="' . esc_url(ditto_webp_url(html_entity_decode($m[1]))) . '"';
  }, $html, 1);
}, 20);

/*========= Página en Apariencia =========*/

function ditto_webp_admin_page() {
  add_theme_page('Imágenes WebP', 'Imágenes WebP', 'edit_theme_options', 'ditto-webp-tools', 'ditto_webp_render_page');
}
add_action('admin_menu', 'ditto_webp_admin_page');

function ditto_webp_render_page() {
  if (!current_user_can('edit_theme_options')) {
    return;
  }

  $supported = wp_image_editor_supports(array('mime_type' => 'image/webp'));
  $writable = wp_mkdir_p(DITTO_WEBP_DIR) && wp_is_writable(DITTO_WEBP_DIR);
  if ($writable && !is_file(DITTO_WEBP_DIR . '/index.php')) {
    @file_put_contents(DITTO_WEBP_DIR . '/index.php', "<?php // Silence is golden.\n");
  }

  $state = get_option(DITTO_WEBP_STATE);
  $enabled = get_option(DITTO_WEBP_ENABLED, '1') === '1';
  ?>
  <div class="wrap">
    <h1>Imágenes WebP</h1>
    <p>Genera una copia WebP de cada imagen de la librería y el sitio la sirve automáticamente.
      <strong>Los archivos originales de <code>uploads</code> nunca se modifican ni se borran.</strong></p>

    <?php if (!$supported): ?>
      <div class="notice notice-error"><p>El servidor no soporta WebP (Imagick o GD sin WebP). Pide al hosting que lo habilite.</p></div>
    <?php endif; if (!$writable): ?>
      <div class="notice notice-error"><p>No se puede escribir en <code><?= esc_html(DITTO_WEBP_DIR); ?></code>.</p></div>
    <?php endif; ?>

    <table class="form-table" role="presentation">
      <tr>
        <th scope="row">Servir WebP en el sitio</th>
        <td><label><input type="checkbox" id="ditto_webp_enabled" <?php checked($enabled); ?>> Activado</label>
          <p class="description">Apágalo para volver a mostrar los originales al instante, sin borrar nada.</p></td>
      </tr>
      <tr>
        <th scope="row"><label for="ditto_webp_quality">Calidad</label></th>
        <td><input id="ditto_webp_quality" type="number" min="40" max="100" value="82"></td>
      </tr>
      <tr>
        <th scope="row">Carpeta de caché</th>
        <td><code><?= esc_html(DITTO_WEBP_DIR); ?></code></td>
      </tr>
    </table>

    <p>
      <button class="button button-primary" id="ditto_webp_start" <?php disabled(!$supported || !$writable); ?>>
        <?= is_array($state) && empty($state['done']) ? 'Continuar conversión' : 'Convertir imágenes'; ?>
      </button>
      <button class="button" id="ditto_webp_restart" <?php disabled(!$supported || !$writable); ?>>Reiniciar desde cero</button>
      <button class="button" id="ditto_webp_stop">Pausar</button>
      <button class="button button-link-delete" id="ditto_webp_clear">Borrar caché WebP</button>
    </p>
    <p class="description">Puedes cerrar la página y volver luego: la conversión continúa donde quedó. Las imágenes ya convertidas se saltan.</p>

    <progress id="ditto_webp_progress" max="100" value="0" style="width:100%;height:18px;"></progress>
    <pre id="ditto_webp_log" style="background:#fff;border:1px solid #ccd0d4;padding:12px;max-height:360px;overflow:auto;"></pre>
  </div>

  <script>
    (function($){
      var running = false;
      var nonce = '<?= esc_js(wp_create_nonce('ditto_webp_tools')); ?>';
      var $log = $('#ditto_webp_log');

      function log(msg) {
        $log.append(msg + "\n").scrollTop($log[0].scrollHeight);
      }

      function report(d) {
        $('#ditto_webp_progress').attr('value', d.dirs_total ? Math.round(d.dirs_done / d.dirs_total * 100) : 0);
        log('Carpetas ' + d.dirs_done + '/' + d.dirs_total + (d.current_dir ? ' (' + d.current_dir + ')' : '') +
            ' | Convertidas: ' + d.converted + ' | Ya existían: ' + d.existing +
            ' | Original más liviano: ' + d.larger + ' | Fallidas: ' + d.failed);
      }

      function step(restart) {
        if (!running) return;
        $.post(ajaxurl, {
          action: 'ditto_webp_process',
          nonce: nonce,
          quality: $('#ditto_webp_quality').val(),
          restart: restart ? 1 : 0
        }).done(function(resp){
          if (!resp || !resp.success) {
            log('Error: ' + (resp && resp.data && resp.data.message ? resp.data.message : 'sin respuesta'));
            running = false;
            return;
          }
          report(resp.data);
          if (resp.data.done) {
            running = false;
            $.each(resp.data.failures || {}, function(file, reason){ log('  Fallida: ' + file + ' -> ' + reason); });
            log('Proceso terminado. Las fallidas siguen mostrando su original.');
          } else {
            step(false);
          }
        }).fail(function(){
          // Un error puntual (p. ej. una imagen enorme) no detiene el proceso: se reintenta y se salta
          log('La petición se interrumpió; reintentando en 3 s...');
          setTimeout(function(){ step(false); }, 3000);
        });
      }

      $('#ditto_webp_start').on('click', function(){
        if (running) return;
        running = true;
        log('Convirtiendo...');
        step(false);
      });

      $('#ditto_webp_restart').on('click', function(){
        if (running || !confirm('¿Reiniciar el recorrido? Las imágenes ya convertidas se saltan igual.')) return;
        running = true;
        log('Reiniciando recorrido...');
        step(true);
      });

      $('#ditto_webp_stop').on('click', function(){
        if (!running) return;
        running = false;
        log('Pausado. Puedes continuar cuando quieras.');
      });

      $('#ditto_webp_clear').on('click', function(){
        if (running || !confirm('¿Borrar todas las copias WebP? Los originales no se tocan.')) return;
        running = true;
        (function clear(){
          $.post(ajaxurl, { action: 'ditto_webp_clear', nonce: nonce }).done(function(resp){
            log('Copias WebP borradas: ' + resp.data.deleted);
            if (resp.data.remaining) { clear(); } else { running = false; log('Caché WebP vacía.'); }
          }).fail(function(){ running = false; log('Error al borrar la caché.'); });
        })();
      });

      $('#ditto_webp_enabled').on('change', function(){
        $.post(ajaxurl, { action: 'ditto_webp_toggle', nonce: nonce, enabled: this.checked ? 1 : 0 });
        log(this.checked ? 'El sitio sirve WebP.' : 'El sitio sirve los originales.');
      });
    })(jQuery);
  </script>
  <?php
}

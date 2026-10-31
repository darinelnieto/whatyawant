<?php
/**
 * 
 * Header.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php /* <title>, description y canonical los genera Yoast vía wp_head() (title-tag) */ ?>
  <?php wp_head(); ?>
  <script>
    const _dittoURI_ = "<?= get_template_directory_uri() ?>",
          _dittoURL_ = "<?= get_site_url() ?>";
  </script>
  <?php 
    $scripts = get_field('head_scripts', 'option');
    if($scripts){
      foreach($scripts as $script) {
        echo $script['script_add'];
      }
    }
  ?>
</head>

<body <?php body_class(); ?>>
<?php 
  $body_script = get_field('tag_body', 'option');
  if($body_script) {
    foreach($body_script as $script) {
      echo $script['script'];
    }
  }
?>
<div id="page"> <!-- +Page container -->

  <header id="header-wrapper">
    <?php get_template_part('partials/globals/header-content'); ?>
  </header>
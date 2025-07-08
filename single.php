<?php
/**
 * 
 * Default single.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$posttype = get_post_type();
switch ($posttype) {
	case 'portfolio':
		get_template_part('templates/single-property-template');
	break;
	case 'post':
		get_template_part('templates/single-blog-template');
	break;
}
?>
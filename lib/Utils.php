<?php
/**
 * Plugin utilities functions.
 *
 * @package one-page-blocks
 * @version 1.0.0
 * @author  Helder Vilela from Pixelthrone
 * @license GPL-3.0
 */
namespace Pixelthrone\OnePage_Blocks\Utils;
use Pixelthrone\OnePage_Blocks\Plugin;

/**
 * Retrieve post meta field for a post.
 *
 * @since 1.5.0
 *
 * @param int    $post_id Post ID.
 * @param string $key     Optional. The meta key to retrieve. By default, returns
 *                        data for all keys. Default empty.
 * @param bool   $single  Optional. Whether to return a single value. Default false.
 * @return mixed Will be an array if $single is false. Will be value of meta data
 *               field if $single is true.
 */
function get_admin_field( $ID, $key, $single = true ) {
	return get_post_meta( $ID, $key, $single);
}

/**
 * Check if the admin bar is showing and return a class
 *
 * @since   1.0.0
 * @version 1.0
 *
 * @return  string
 */
function ui_admin_bar( $return = 'echo' ) {
	$var = ( is_admin_bar_showing() ) ? '-admin-bar' : '';

	if ( $return == 'echo' ) {
		echo $var;
	} else {
		return $var;
	}
}

/**
 * Check current post type
 *
 * @since   1.0.0
 * @version 1.0
 *
 * @return  string
 */
function is_post_type_page( $check=false ) {
	global $pagenow;

	if( isset($_GET['post_type']) && $_GET['post_type'] === $check ) {
		return true;
	}

	if( isset($_GET['post']) && $pagenow === 'post.php' ) {
		$post = get_post( $_GET['post'] );
		if( $post->post_type === 'one-page-blocks') {
			return true;
		}
	}

	return false;
}
/**
 * Get current post type
 *
 * @since   1.0.0
 * @version 1.0
 *
 * @return  string
 */
function get_post_type() {
	global $pagenow;

	if ('post.php' == $pagenow && isset($_GET['post']) ) {
		$post_type = get_post_type($_GET['post']);
		return $post_type;
	}

	if ( 'post-new.php' == $pagenow && isset($_GET['post_type']) ) {
		return $_GET['post_type'];
	}

	return null;
}

/**
 * Get html component
 *
 * @since  1.0.0
 * @access public
 *
 * @param $name
 * @param $args
 *
 * @return string
 */
function component( $name, $args = [] ) {
	if( ! empty($args) && is_array( $args ) ) {
		extract( $args ); // @codingStandardsIgnoreLine
	}
	include Plugin::get_plugin_dir( '/frontend/components/' . $name . '.php' );
}

/**
 * Esc string with allowed html
 *
 * @since  1.0.0
 * @access public
 *
 * @param $name
 * @param $args
 *
 * @return string
 */
function esc_allowed_html( $string ) {
	$allowed_html = [
		'a' => [
			'href' => [],
			'title' => [],
			'target' => [],
			'class' => [],
		],
		'p' => [
			'class' => [],
		],
		'strong' => [],
		'em' =>[],
		'u' =>[],
		'b' =>[],
		'br' =>[]
	];

	return wp_kses($string, $allowed_html);
}

/**
 * Load SVG Icon
 *
 * @since    1.0.0
 * @version  1.0
 *
 * @param $svg_name
 *
 * @return string
 */
function loadSVG( $svg_name, $return = false, $path = '/public/svg/' ) {
	if ( $return ) {
		ob_start();
		include Plugin::get_plugin_dir($path . $svg_name . '.svg');
		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	} else {
		include Plugin::get_plugin_dir($path . $svg_name . '.svg');
	}
}

/**
 * Encrypt string
 *
 * @since    1.0.0
 * @version  1.0
 *
 * @param $string
 *
 * @return string
 */
function encrypt( $string ) {
	return md5($string.AUTH_KEY);
}

/**
 * Encrypt string
 *
 * @since    1.0.0
 * @version  1.0
 *
 * @param $string
 *
 * @return string
 */
function check_encrypt( $plain_string, $encrypted_string ) {
	return md5($plain_string.AUTH_KEY) === $encrypted_string;
}

/**
 * Build block typography css
 *
 * @since    1.0.0
 * @version  1.0
 *
 * @param $typo
 * @param $className
 *
 * @return string
 */
function compile_block_typography_css( $blockID, $typo, $className ) {

	$fontStyle = 'normal';
	$fontweight = $typo->desktop->fontweight;

	if (strpos($fontweight, 'italic') !== false) {
		$fontweight = str_replace("italic", "", $fontweight); // Remove italic
		$fontweight = $fontweight ? $fontweight : 'normal'; // Check if is empty
		$fontStyle = 'italic';
	}
	if (strpos($fontweight, 'regular') !== false) {
		$fontweight = 'normal';
	}

	// Desktop
	$CSS[] = "main[data-blockid=\"{$blockID}\"] .-{$className} {
				font-family: {$typo->fontfamily};
				font-size: {$typo->desktop->fontsize}px;
				font-weight: {$fontweight};
				font-style: {$fontStyle};
				line-height: {$typo->desktop->lineheight}%;
				letter-spacing: {$typo->desktop->letterspacing}px;
			}";

	// Mobile
	$CSS[] = "@media only screen and (max-width: 1023px) {
				main[data-blockid=\"{$blockID}\"] .-{$className} {
					font-family: {$typo->fontfamily};
					font-size: {$typo->mobile->fontsize}px;
					font-weight: {$fontweight};
					font-style: {$fontStyle};
					line-height: {$typo->mobile->lineheight}%;
					letter-spacing: {$typo->mobile->letterspacing}px;
				}
			}";

	return implode($CSS, ' ');
}

/**
 * Add page CSS
 *
 * @since    1.0.0
 * @version  1.0
 *
 * @param $action
 * @param $CSS
 *
 * @return array
 */
function page_css( $action, $CSS=null) {
	if( $action === 'add' ) {
		Plugin::$page_CSS[] = $CSS;
	} else {
		return Plugin::$page_CSS;
	}
}

/**
 * Add page CSS
 *
 * @since    1.0.0
 * @version  1.0
 *
 * @param $font
 * @param $action
 *
 * @return array
 */
function page_fonts( $action, $font=null ) {
	if( $action === 'add' ) {
		if( ! isset( Plugin::$google_fonts[$font->fontfamily][$font->desktop->fontweight] ) ) {
			Plugin::$google_fonts[$font->fontfamily][$font->desktop->fontweight] = $font->desktop->fontweight;
		}
		if( ! isset( Plugin::$google_fonts[$font->fontfamily][$font->mobile->fontweight] ) ) {
			Plugin::$google_fonts[$font->fontfamily][$font->mobile->fontweight] = $font->mobile->fontweight;
		}
	} else {
		return Plugin::$google_fonts;
	}
}

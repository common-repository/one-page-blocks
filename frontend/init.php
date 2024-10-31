<?php
/**
 * Main class to manage frontend view.
 *
 * @package   One Page Blocks
 * @author    Helder Vilela from Pixelthrone
 * @link      support@pixelthrone.com
 * @license   GPL-3.0
 */

namespace Pixelthrone\OnePage_Blocks\Frontend;

use Pixelthrone\OnePage_Blocks\Utils;
use Pixelthrone\OnePage_Blocks\Plugin;
use WP_Error;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Frontend Class
 *
 * @since 1.0.0
 */
class Init {

	/**
	 * One Page status.
	 *
	 * @var string $status
	 */
	private $status;

	/**
	 * Class constructor.
	 */
	public function __construct() {

		// Only run on frontend
		if( is_admin() )
			return;

		// Check if plugin is active
		if( Plugin::is_active() ) {
			add_action( 'template_redirect', [$this, 'template_redirect'] );
		}
	}

	/**
	 * Build page.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string.
	 */
	public
	function template_redirect() {
		$pageSettings = Plugin::get_frontend_settings();

		// Check if page exist
		$post = get_post( $pageSettings->selected_page_id );

//		print_r( "<pre>" );
//		print_r( $pageSettings );
//		print_r( "</pre>" );

		/**
		 * Verify if the page has the correct status.
		 *
		 * @since 1.0.0
		 */
		if( ! $post || $post->post_status !== 'publish') {
			return false;
		}

		/**
		 * Exit if a custom login page.
		 *
		 * @since 1.0.0
		 */
		if(preg_match("/login|admin|dashboard|account/i",$_SERVER['REQUEST_URI']) > 0 ){
			return false;
		}

		include_once( Plugin::get_plugin_dir().'/frontend/template.php' );
		exit();

	}
}

if ( Plugin::has_gutenberg() && ! is_admin() ) {
	new Init();
}

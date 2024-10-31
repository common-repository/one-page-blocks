<?php
/**
 * Init gutenberg blocks.
 *
 * @package   One Page Blocks
 * @author    Helder Vilela from Pixelthrone
 * @link      support@pixelthrone.com
 * @license   GPL-3.0
 */

namespace Pixelthrone\OnePage_Blocks\Dashboard;

use Pixelthrone\OnePage_Blocks\Plugin;
use Pixelthrone\OnePage_Blocks\Utils;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main dashboard class
 *
 * @since 1.0.0
 */
class Init {
	/**
	 * Plugin url.
	 *
	 * @var string $plugin_url
	 */
	private $url;

	/**
	 * Plugin slug.
	 *
	 * @var string $slug
	 */
	private $slug;

	/**
	 * Plugin slug.
	 *
	 * @var string $slug
	 */
	private $version;

	/**
	 * Plugin settings.
	 *
	 * @var string $settings
	 */
	private $settings;

	/**
	 * Blocks manifest.
	 *
	 * @var array
	 */
	private $blocks;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public
	function __construct() {
		global $pagenow;

		$this->slug    = Plugin::get_slug();
		$this->url     = Plugin::get_plugin_url();
		$this->version = Plugin::get_version();
		$this->settings = Plugin::get_settings();

		if ( Plugin::has_gutenberg() && Utils\is_post_type_page( Plugin::get_slug() ) && $pagenow === 'edit.php' ) {


			add_action( 'admin_footer', function () {
				//				echo '<div id="plugin-dashboard"></div>';
			} );

			add_action( 'enqueue_block_editor_assets', [ $this, 'localization' ] );
			add_action( 'admin_init', [ $this, 'load_dashboard_assets' ] );
		}

		add_action( 'init', [ $this, 'init' ], 100 );
		//add_action( 'admin_menu', [ $this, 'admin_menu' ] );
	}

	/**
	 * Init backend & frontend code.
	 *
	 * @access public
	 */
	public
	function init() {
		/**
		 * Register post type.
		 */
		register_post_type( $this->slug, $this->settings['post_type'] );
		/**
		 * Enqueue global assets.
		 */
		wp_enqueue_style( $this->slug . '-global', $this->url . '/public/dist/global.bundle.css', [], $this->version );
	}

	/**
	 * Register a custom menu page.
	 *
	 * @access public
	 */
	public
	function admin_menu() {}

	/**
	 * Enqueue dashboard assets.
	 *
	 * @access public
	 */
	public
	function load_dashboard_assets() {
		wp_enqueue_style( 'wp-components' );
		wp_enqueue_style( 'wp-editor' );
		wp_enqueue_style( $this->slug . '-dashboard', $this->url . '/public/dist/dashboard.bundle.css', [
			'wp-components',
			'wp-editor'
		], $this->version );

		wp_enqueue_script( 'wp-components' );
		wp_enqueue_script( 'wp-editor' );
		wp_enqueue_script( 'wp-i18n' );
		wp_enqueue_script( 'wp-api' );
		wp_enqueue_script( $this->slug . '-dashboard', $this->url . '/public/dist/dashboard.bundle.js', [
			'wp-i18n',
			'wp-components',
			'wp-editor',
			'wp-api',
		], $this->version, true );
	}

	/**
	 * Enqueue Jed-formatted localization data.
	 *
	 * @access public
	 */
	public
	function localization() {}
}

new Init();


<?php
/**
 * Check for Gutenberg.
 *
 * @package   One Page Blocks
 * @author    Helder Vilela from Pixelthrone
 * @link      support@pixelthrone.com
 * @license   GPL-3.0
 */

namespace Pixelthrone\OnePage_Blocks\Lib\Gutenberg_Checker;
use Pixelthrone\OnePage_Blocks\Plugin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Notice Class
 *
 * @since 1.0.0
 */
class Gutenberg_Checker {

	/**
	 * Gutenberg check.
	 *
	 * @var string $has_gutenberg
	 */
	public $has_gutenberg = false;

	/**
	 * Gutenberg base.
	 *
	 * @var string $gutenberg_base
	 */
	public $gutenberg_base;

	/**
	 * Gutenberg slug.
	 *
	 * @var string $gutenberg_slug
	 */
	public $gutenberg_slug = 'gutenberg';

	/**
	 * Classic Editor check.
	 *
	 * @var string $has_classic_editor
	 */
	public $has_classic_editor;

	/**
	 * Classic Editor slug.
	 *
	 * @var string $classic_editor_slug
	 */
	public $classic_editor_slug = 'classic-editor';

	/**
	 * Classic Editor base.
	 *
	 * @var string $classic_editor_base
	 */
	public $classic_editor_base;

	/**
	 * Setup the activation class.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public
	function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ));
	}

	/**
	 * Check if plugins are installed.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public
	function admin_init() {
		$plugins = get_plugins();

		// Check if Gutenberg is installed.
		foreach( $plugins as $plugin_path => $plugin ) {
			if( $plugin['TextDomain'] === $this->gutenberg_slug ) {
				$this->has_gutenberg = true;
				$this->gutenberg_base = $this->gutenberg_slug;
			}
		}

		// Check if Classic Editor is installed.
		foreach ( $plugins as $plugin_path => $plugin ) {
			if( $plugin['TextDomain'] === $this->classic_editor_slug ) {
				$this->has_classic_editor = true;
				$this->classic_editor_base = $plugin_path;
			}
		}

		/**
		 * Fire notices where needed
		 */
		// Check if the Gutenberg plugin exists.
		if ( ! function_exists( 'register_block_type' ) ) {
			if(version_compare(get_bloginfo('version'),'5', '>=') ){
				add_action( 'admin_notices', array( $this, 'wp5_notice' ) );
			} else {
				add_action( 'admin_notices', array( $this, 'wp4_notice' ) );
			}
		}

		// Check if the Classic Editor plugin exists.
		if ( function_exists( 'classic_editor_init_actions' ) && $this->has_classic_editor ) {
			add_action( 'admin_notices', array( $this, 'classic_editor_notice' ) );
		}
	}

	/**
	 * Display notice if Gutenberg is not installed or activated.
	 *
	 * @access public
	 */
	public function classic_editor_notice() {

		$url = wp_nonce_url( admin_url( 'plugins.php?action=deactivate&plugin=' . Plugin::get_settings('3party', 'gutenberg', 'slug') ), 'deactivate-plugin_' . Plugin::get_settings('3party', 'gutenberg', 'name') );

		/* translators: Name of this pluign */
		$link = '<a class="button" href="' . esc_url( $url ) . '">' . sprintf( __( 'deactivate %1$s', 'one-page-blocks' ), 'Classic Editor' ) . '</a>';

		$plugin = '<a href="http://wordpress.org/gutenberg" target="_blank">Gutenberg</a>';

		/* translators: 1: Required plugin 2: Name of this plugin 3: Activate or Install, from $link above */
		echo '<div class="notice notice-error"><p>' . sprintf( esc_html__( '%1$s requires the %2$s block editor. %4$s Please %3$s to continue.', 'one-page-blocks' ), '<strong>' . Plugin::get_settings('3party', 'gutenberg', 'name') . '</strong>', $plugin, $link, '<br>' ) . '</p></div>';
	}

	/**
	 * Display notice if Gutenberg is not installed or activated for wordpress 5
	 *
	 * @access public
	 */
	public
	function wp5_notice() {
	}

	/**
	 * Display notice if Gutenberg is not installed or activated.
	 *
	 * @access public
	 */
	public
	function wp4_notice() {
		if ( $this->has_gutenberg ) {
			$url = wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin=' . Plugin::get_settings('3party', 'gutenberg', 'base') ).'&plugin_status=all&paged=1', 'activate-plugin_' . Plugin::get_settings('3party', 'gutenberg', 'base') );
			/* translators: Name of this pluign */
			$link = '<a class="button" href="' . esc_url( $url ) . '">' . sprintf( __( 'activate %1$s', 'one-page-blocks' ), Plugin::get_settings('3party', 'gutenberg', 'name') ) . '</a>';
		} else {
			$url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . Plugin::get_settings('3party', 'gutenberg', 'slug') ), 'install-plugin_' . Plugin::get_settings('3party', 'gutenberg', 'slug') );
			/* translators: Name of this pluign */
			$link = '<a class="button" href="' . esc_url( $url ) . '">' . sprintf( __( 'install %1$s', 'one-page-blocks' ), Plugin::get_settings('3party', 'gutenberg', 'name') ) . '</a>';
		}

		$plugin = '<a href="http://wordpress.org/gutenberg" target="_blank">'.Plugin::get_settings('3party', 'gutenberg', 'name').'</a>';

		/* translators: 1: Required plugin 2: Name of this pluign 3: Activate or Install, from $link above */

		echo '<div class="notice notice-error"><p>' . sprintf( esc_html__( '%1$s requires the %2$s block editor. %4$s Please %3$s to continue.', 'one-page-blocks' ), '<strong>' . Plugin::get_settings('plugin', 'name') . '</strong>', $plugin, $link, '<br>' ) . '</p></div>';
	}
}

new Gutenberg_Checker();




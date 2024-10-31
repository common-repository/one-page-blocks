<?php
/**
 * Init gutenberg blocks.
 *
 * @package   One Page Blocks
 * @author    Helder Vilela from Pixelthrone
 * @link      support@pixelthrone.com
 * @license   GPL-3.0
 */

namespace Pixelthrone\OnePage_Blocks\Blocks;

use Pixelthrone\OnePage_Blocks\Plugin;
use Pixelthrone\OnePage_Blocks\Utils;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main blocks class
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
		// No run in ajax
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		add_action( 'rest_insert_' . Plugin::get_slug(), [ $this, 'post_updated_on_rest' ], 10, 2 );
		add_action( 'post_updated', [ $this, 'post_updated_on_admin' ], 10, 3 );

		// Only on my pages
		if ( ! Utils\is_post_type_page( Plugin::get_slug() ) && is_admin() ) {
			return;
		}

		$this->slug    = Plugin::get_slug();
		$this->url     = Plugin::get_plugin_url();
		$this->version = Plugin::get_version();
		$this->blocks  = wp_remote_get( $this->url . '/admin/blocks/available-blocks.json' );
		$this->blocks  = json_decode( wp_remote_retrieve_body( $this->blocks ) );

		// Only run on my page
		if ( Utils\is_post_type_page( Plugin::get_slug() ) ) {
			add_action( 'admin_init', [ $this, 'admin_init' ] );
			add_action( 'after_setup_theme', [ $this, 'after_setup_theme' ], 99 );
			add_action( 'enqueue_block_editor_assets', [ $this, 'localization' ] );
			add_action( 'admin_footer', [ $this, 'admin_footer' ] );
		}
		add_action( 'init', [ $this, 'init' ] );
	}

	/**
	 * Update post meta on rest call.
	 *
	 * @access public
	 *
	 * @param $post
	 * @param $WP_REST_Request | WP_REST_Request
	 */
	public
	function post_updated_on_rest( $post, $WP_REST_Request ) {

		$status              = $WP_REST_Request->get_param( 'status' );
		$seo_content         = $WP_REST_Request->get_param( 'onepageblocks_seo' );
		$lookandfeel_content = $WP_REST_Request->get_param( 'onepageblocks_lookandfeel' );
		$settings_content    = $WP_REST_Request->get_param( 'onepageblocks_settings' );

		if ( ! $seo_content && ! $lookandfeel_content && ! $settings_content ) {
			return;
		}

		$posted_id               = $WP_REST_Request->get_param( 'id' );
		$actual_selected_page_id = Plugin::get_option( 'selected_page_id' );

		if ( $seo_content ) {
			Plugin::update_option( 'seo', $posted_id, $seo_content );
		}

		if ( $lookandfeel_content ) {
			Plugin::update_option( 'lookandfeel', $posted_id, $lookandfeel_content );
		}

		if ( $settings_content ) {
			Plugin::update_option( 'settings', $posted_id, $settings_content );

			$settings_content = json_decode( $settings_content );

			// Same Page
			if ( intval( $actual_selected_page_id ) === intval( $posted_id ) ) {
				$new_page_id = $settings_content->status ? $posted_id : null;
				Plugin::update_option( 'selected_page_id', null, $new_page_id );
			}
			// Different page
			else {
				if ( $settings_content ) {
					Plugin::update_option( 'selected_page_id', null, $posted_id );
				}
			}


		}

	}

	/**
	 * Update post meta on normal call.
	 *
	 * @access public
	 */
	public
	function post_updated_on_admin( $post_ID, $post_after, $post_before ) {
		$actual_selected_page_id = Plugin::get_option( 'selected_page_id' );

		if( $post_after->post_status !== 'publish' ) {
			if ( intval( $actual_selected_page_id ) === intval( $post_ID ) ) {
				Plugin::update_option( 'selected_page_id', null, null );
			}
		}

	}

	/**
	 * Registers theme support for a given feature.
	 *
	 * @access public
	 */
	public
	function after_setup_theme() {
		remove_theme_support( 'editor-styles' );
		remove_theme_support( 'dark-editor-style' );
		remove_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-full' );
		remove_theme_support( 'disable-custom-colors' );
		add_theme_support( 'editor-font-sizes' );
	}

	/**
	 * Init on frontend.
	 *
	 * @access public
	 */
	public
	function init() {
		$this->register_blocks();
	}

	/**
	 * Init on admin.
	 *
	 * @access public
	 */
	public
	function admin_init() {
		$this->register_blocks();
		$this->editor_assets();
	}

	/**
	 * Add actions to enqueue assets.
	 *
	 * @access public
	 */
	public
	function register_blocks() {

		// Return early if this function does not exist.
		if ( ! function_exists( 'register_block_type' ) || ! is_array( $this->blocks ) ) {
			return;
		}

		/**
		 * Register Block.
		 */
		foreach ( $this->blocks as $block ) {

			$args = [
				'editor_script' => $this->slug . '-editor',
				'editor_style'  => $this->slug . '-editor',
				'style'         => $this->slug . '-frontend',
			];

			if ( ! empty( $block->script ) ) {
				$args['script'] = $this->slug . '-' . $args['script'];
			}

			if ( $block->render_callback ) {
				$args['attributes']      = include( Plugin::get_plugin_dir( '/admin/blocks/_available/' . $block->slug . '/attributes.php' ) );
				$args['render_callback'] = include( Plugin::get_plugin_dir( '/admin/blocks/_available/' . $block->slug . '/render.php' ) );
			}

			register_block_type( $block->name, $args );
		}

	}

	/**
	 * Enqueue block assets for use within Gutenberg.
	 *
	 * @access public
	 */
	public
	function editor_assets() {

		// Styles
		wp_register_style( $this->slug . '-frontend', $this->url . '/public/dist/blocks.editor.bundle.css', [], $this->version );
		wp_register_style( $this->slug . '-editor', $this->url . '/public/dist/blocks.style.bundle.css', [], $this->version );

		// Scripts
		wp_register_script( $this->slug . '-editor', $this->url . '/public/dist/blocks.bundle.js', [
			'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-plugins',
			'wp-components',
			'wp-edit-post',
			'wp-api',
			'wp-editor'
		], $this->version, true );

	}

	/**
	 * Enqueue Jed-formatted localization data.
	 *
	 * @access public
	 */
	public
	function localization() {

		/**
		 * Share page data with javascript.
		 *
		 * @since   1.0.0
		 */
		$ptPlugin = Plugin::get_script_data();
		$ptPlugin['license'] = [
			'plan'                 => opblocks_fs()->get_plan_name(),
			'can_use_premium_code' => opblocks_fs()->can_use_premium_code(),
			'is_trial'             => opblocks_fs()->is_trial(),
			'is_free'              => opblocks_fs()->is_not_paying(),
		];

		$ptPlugin = wp_json_encode( $ptPlugin );
		$content = [
			'const opblocks=' . $ptPlugin . ';'
		];

		wp_script_add_data( $this->slug . '-editor', 'data', implode( $content, ' ' ) );


		// Check if this function exists.
		if ( ! function_exists( 'wp_set_script_translations' ) ) {
			return;
		}

		wp_set_script_translations( $this->slug . '-editor', 'one-page-blocks' );
	}

	/**
	 * Add extra markup.
	 *
	 * @access public
	 */
	public
	function admin_footer() {}
}

if ( Plugin::has_gutenberg() ) {
	new Init();
}


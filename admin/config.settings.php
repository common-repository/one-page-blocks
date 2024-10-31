<?php
/**
 * plugin config file.
 *
 * @package   One Page Blocks
 * @author    Helder Vilela from Pixelthrone
 * @link      support@pixelthrone.com
 * @license   GPL-3.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Pixelthrone\OnePage_Blocks\Plugin;

$icon = Plugin::is_active() ? 'dashicons-welcome-view-site' : 'dashicons-welcome-view-site';
$cdnURL = 'https://cdn.pixelthrone.com/';

return [
	/**
	 * Plugin.
	 */
	'plugin'    => [
		'slug'      => 'one-page-blocks',
		'name'      => 'One Page Blocks',
		'version'   => '1.0.0',
		'PluginURI' => 'https://pixelthrone.com/one-page-blocks',
		'WP.OrgURI' => '#plugin-wp-org-url',
	],
	/**
	 * Meta keys.
	 */
	'meta_keys' => [
		'seo'              => 'onepageblocks_seo',
		'settings'         => 'onepageblocks_settings',
		'lookandfeel'      => 'onepageblocks_lookandfeel',
		'selected_page_id' => 'onepageblocks_selected_page_id',
	],
	/**
	 * Cdn.
	 */
	'cdn'    => [
		'media' => $cdnURL.'media/one-page-blocks/',
		'notices' => $cdnURL.'notices/one-page-blocks/',
	],
	/**
	 * 3rd-Party plugins.
	 */
	'3party'    => [
		'gutenberg' => [
			'slug' => 'gutenberg',
			'name' => 'gutenberg',
			'base' => 'gutenberg/gutenberg.php',
		]
	],
	/**
	 * Post Type.
	 */
	'post_type' => [
		'menu_icon'           => $icon,
		'label'               => esc_html__( 'One Page', 'one-page-blocks' ),
		'labels'              => [
			'name'               => esc_html__( 'Pages', 'one-page-blocks' ),
			'singular_name'      => esc_html__( 'Page', 'one-page-blocks' ),
			'menu_name'          => esc_html__( 'One Page', 'one-page-blocks' ),
			'parent_item_colon'  => esc_html__( 'Page', 'one-page-blocks' ),
			'all_items'          => esc_html__( 'All Pages', 'one-page-blocks' ),
			'view_item'          => esc_html__( 'View Pages', 'one-page-blocks' ),
			'add_new_item'       => esc_html__( 'Add new', 'one-page-blocks' ),
			'add_new'            => esc_html__( 'Add new', 'one-page-blocks' ),
			'edit_item'          => esc_html__( 'Edit Pages', 'one-page-blocks' ),
			'update_item'        => esc_html__( 'Update', 'one-page-blocks' ),
			'search_items'       => esc_html__( 'Search pages', 'one-page-blocks' ),
			'not_found'          => esc_html__( 'No pages found', 'one-page-blocks' ),
			'not_found_in_trash' => esc_html__( 'No pages found in Trash', 'one-page-blocks' ),
		],
		'supports'            => [ 'title', 'revisions', 'editor' ],
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'can_export'          => true,
		'has_archive'         => true,
		'show_in_rest'        => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	],
	/**
	 * toolbar.
	 */
	'toolbar'   => [
		[
			'id'    => 'pt_onepageblocks_toolbar_group',
			'title' => '<i class="dashicons-before '.$icon.'"></i>',
			'href'  => get_admin_url( null, 'edit.php?post_type=one-page-blocks' ),
			'meta'  => [ 'class' => '' ]
		],
		[
			'id'     => 'pt_onepageblocks_toolbar_add-new',
			'title'  => sprintf( esc_html__( '%s Add One Page', 'one-page-blocks' ), '<i class="dashicons-before dashicons-plus"></i>' ),
			'href'   => get_admin_url( null, 'post-new.php?post_type=one-page-blocks' ),
			'parent' => 'pt_onepageblocks_toolbar_group'
		],
		[
			'id'     => 'pt_onepageblocks_toolbar_settings',
			'title'  => sprintf( esc_html__( '%s Settings', 'one-page-blocks' ), '<i class="dashicons-before dashicons-admin-generic"></i>' ),
			'href'   => get_admin_url( null, 'edit.php?post_type=one-page-blocks' ),
			'parent' => 'pt_onepageblocks_toolbar_group'
		],
	]
];

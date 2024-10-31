<?php
/**
 * Helper function for easy freemius access.
 *
 * @package   One Page Blocks
 * @author    Helder Vilela from Pixelthrone
 * @link      support@pixelthrone.com
 * @license   GPL-3.0
 */

// Create a helper function for easy SDK access.
if ( ! function_exists( 'opblocks_fs' ) ) {
	// Create a helper function for easy SDK access.
	function opblocks_fs() {
		global $opblocks_fs;

		if ( ! isset( $opblocks_fs ) ) {
			// Activate multisite network integration.
			if ( ! defined( 'WP_FS__PRODUCT_2938_MULTISITE' ) ) {
				define( 'WP_FS__PRODUCT_2938_MULTISITE', true );
			}

			// Include Freemius SDK.
			require_once dirname( __FILE__ ) . '/freemius/start.php';

			$opblocks_fs = fs_dynamic_init( array(
				                                'id'                  => '2938',
				                                'slug'                => 'one-page-blocks',
				                                'type'                => 'plugin',
				                                'public_key'          => 'pk_cda0a94a7dc398a46941ff35e1c55',
				                                'is_premium'          => false,
				                                // If your plugin is a serviceware, set this option to false.
				                                'has_premium_version' => false,
				                                'has_addons'          => false,
				                                'has_paid_plans'      => false,
				                                'menu'                => array(
					                                'slug' => 'edit.php?post_type=one-page-blocks',
				                                ),
			                                ) );
		}

		return $opblocks_fs;
	}

	// Init Freemius.
	opblocks_fs();
	// Signal that SDK was initiated.
	do_action( 'opblocks_fs_loaded' );
}

// Core overrides.
opblocks_fs()->override_i18n( array(
	                              'symbol_arrow-left'  => '',
	                              'symbol_arrow-right' => '',
                              ) );
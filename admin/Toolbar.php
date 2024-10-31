<?php
/**
 * Class to add menu to toolbar.
 *
 * @package   One Page Blocks
 * @author    Helder Vilela from Pixelthrone
 * @link      support@pixelthrone.com
 * @license   GPL-3.0
 */

namespace Pixelthrone\OnePage_Blocks\Toolbar;
use Pixelthrone\OnePage_Blocks\Plugin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Toolbar Class
 *
 * @since 1.0.0
 */
class Toolbar {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'admin_bar_menu', [$this, 'admin_bar_menu'] , 50 );
	}

	/**
	 * Build menu.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $footer_text The content that will be printed.
	 *
	 * @return string The content that will be printed.
	 */
	function admin_bar_menu( $wp_admin_bar ) {

		foreach (Plugin::get_settings('toolbar') as $menu) {

			if( Plugin::is_active() ) {
				if( $menu['id'] === 'pt_onepageblocks_toolbar_group' ) {
					$menu['meta'] = ['class' => '-one-page-block-is-enabled'];
				}
			}
			$wp_admin_bar->add_node( $menu );

		}
	}

}

new Toolbar();

<?php
/**
 * Unlock Coming Soon Page
 *
 * @package one-page-blocks
 * @version 1.0.0
 * @author  Helder Vilela from Pixelthrone
 * @license GPL-3.0
 */

namespace Pixelthrone\OnePage_Blocks\Api\UnlockPage;

use Pixelthrone\OnePage_Blocks\Utils;
use Pixelthrone\OnePage_Blocks\Plugin;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WP_Error;

class UnlockPage {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'v1';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_endpoint = 'unlockpage';

	public
	function __construct() {

		add_action( 'rest_api_init', function () {

			/**
			 * Filter feed
			 *
			 * @since    1.0.0
			 * @access   public
			 * @endpoint /wp-json/v1/unlockpage (GET)
			 *
			 * @return object
			 */
			register_rest_route( $this->namespace, '/' . $this->rest_endpoint, [
				'methods'  => WP_REST_Server::READABLE,
				'callback' => [ $this, 'callback' ],
			] );


		} );

	}

	/**
	 * Get cart.
	 *
	 * @access public
	 * @since  1.0.0
	 *
	 * @param  array $data
	 *
	 * @return WP_REST_Response
	 */
	public
	function callback( WP_REST_Request $request ) {
		$nonce = check_ajax_referer('wp_rest', '_wpnonce', false);
		$pwd = $request->get_param( 'pwd' );

		# Check the nonce
		if ( ! $nonce ) {
			return new WP_Error( 'invalid-nonce', esc_html__( 'No naughty business please!', 'one-page-blocks' ), [ 'status' => 403 ] );
		}

		$pageConfig = Plugin::get_frontend_settings();

		if( $pageConfig->settings->passwordProtected ) {

			// Confirm password
			if( $pageConfig->settings->password === $pwd ) {
				$response = [
					'code' => 'refreshing',
					'data' => [
						'status' => 200,
					],
					'message'  => esc_html__( 'The page will be updated.', 'one-page-blocks' )
				];

				$pwd = Utils\encrypt($pwd);
				setcookie( 'onepageblocks__unlock_page', $pwd, strtotime( '+10 days' ), '/' );

				return new WP_REST_Response( $response, 200 );
			}
			// Invalid password
			else {
				return new WP_Error( 'invalid-password', esc_html__( 'The password is not correct.', 'one-page-blocks' ), [ 'status' => 403 ] );
			}


		} else {
			return new WP_Error( 'option-is-not-enabled', esc_html__( 'No naughty business please!', 'one-page-blocks' ), [ 'status' => 403 ] );
		}

	}

}

new UnlockPage();




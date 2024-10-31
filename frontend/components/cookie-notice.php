<?php
/**
 * Cookie notice component.
 *
 * @package   One Page Blocks
 * @author    Helder Vilela from Pixelthrone
 * @link      support@pixelthrone.com
 * @license   GPL-3.0
 */

use Pixelthrone\OnePage_Blocks\Utils;

if( isset($_COOKIE['onepageblocks__cookie_notice']) && $_COOKIE['onepageblocks__cookie_notice'] === 'true') {
	return true;
}

?>
<div data-component="cookie-notice" class="<?php echo $notalone ? '-not-alone' : ''; ?>">
	<p><?php echo Utils\esc_allowed_html( '<b>We use <u>cookies</u></b> to ensure that we give you the best experience on our website.<br> If you continue to use this page we will assume that you are happy with it.', 'one-page-blocks' ); ?></p>
	<button><?php esc_html_e( 'Got it, Thanks!', 'one-page-blocks' );?></button>
</div>

<?php
/**
 * Block background overlayer.
 *
 * @package   One Page Blocks
 * @author    Helder Vilela from Pixelthrone
 * @link      support@pixelthrone.com
 * @license   GPL-3.0
 */

if( $overlayOpacity !== 0 ):;
?>
	<div data-component="block-background-overlayer"
	     style="background-color: <?php echo esc_attr($overlayColor) ?>; opacity: <?php echo esc_attr($overlayOpacity/100) ?>;"
	></div>
<?php endif; ?>
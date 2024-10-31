<?php
/**
 * Social widget component.
 *
 * @package   One Page Blocks
 * @author    Helder Vilela from Pixelthrone
 * @link      support@pixelthrone.com
 * @license   GPL-3.0
 */
if( empty($networks)) {
	return;
}

$networks = json_decode( $networks );
?>
<div data-component="social-icons">
	<?php foreach( $networks as $network ):?>
		<a href="<?php echo esc_url($network->url); ?>" target="_blank" class="-icon -<?php echo esc_attr($network->icon); ?>"><i class="socicon-<?php echo esc_attr($network->icon); ?>"></i></a>
	<?php endforeach; ?>
</div>

<?php
/**
 * Unlock page button component.
 *
 * @since 1.0.0
 *
 * @package   One Page Blocks
 * @author    Helder Vilela from Pixelthrone
 * @link      support@pixelthrone.com
 * @license   GPL-3.0
 */
use Pixelthrone\OnePage_Blocks\Utils;
$button_bg = ( isset($button_bg) && ! empty($button_bg) ) ? "style='background-color:{$button_bg};'" : '';
?>
<div data-component="unlock-page" data-mode="close"  data-message="false"  data-loading="false">
	<a href="#" class="button">
		<?php Utils\loadSVG('icon--password-protected'); ?>
		<i></i>
		<span <?php echo $button_bg; ?>></span>
	</a>
	<div class="content__wrapper">
		<span><p></p></span>
		<p><?php esc_html_e( 'This site is password protected, view by typing the password.', 'one-page-blocks' ); ?></p>
		<input type="password" name="password" placeholder="∙∙∙∙∙∙">
		<i></i>
	</div>

	<span class="page-overlayer"></span>
</div>
<?php
use Pixelthrone\OnePage_Blocks\Utils;

/**
 * @param $attributes
 * @param $content
 *
 * @return false|string
 */
return function( $attributes, $content ) {

	// Not render on backend
	if( is_admin() ) {
		return null;
	}

	$blockID = uniqid('block__');

	/**
	 * Typography
	 */
	$bodyTypo = json_decode($attributes['bodyTypo']);
	Utils\page_css( 'add', Utils\compile_block_typography_css( $blockID, $bodyTypo, 'body__typography' ) );
	Utils\page_fonts( 'add', $bodyTypo );

	$titleTypo = json_decode( $attributes['titleTypo'] );
	Utils\page_css( 'add', Utils\compile_block_typography_css( $blockID, $titleTypo, 'title__typography' ) );
	Utils\page_fonts( 'add', $titleTypo );

	/**
	 * Colors
	 */
	Utils\page_css( 'add', "main[data-blockid=\"{$blockID}\"] { color: {$attributes['textColor']}; }" );
	Utils\page_css( 'add', "main[data-blockid=\"{$blockID}\"] a { color: {$attributes['linkColor']}; }" );

	/**
	 * Output
	 */
	ob_start();
	?>
	<main data-blockid="<?php echo $blockID; ?>" data-block="pixelthrone/onepage--temp-01">

		<div class="content__container -body__typography" style="color:<?php echo esc_attr($attributes['textColor']); ?>; max-width: <?php echo esc_attr($attributes['contentMaxWidth']); ?>px;">
			<h1 class="-title__typography"><?php echo Utils\esc_allowed_html($attributes['titleText']); ?></h1>
			<p><?php echo Utils\esc_allowed_html($attributes['subtitleText']); ?></p>
		</div>

		<?php Utils\component('block-background-overlayer', ['overlayColor'=> $attributes['overlayColor'], 'overlayOpacity' => $attributes['overlayOpacity'] ]) ?>
		<?php Utils\component('block-background', ['background'=> $attributes['background'] ]) ?>
	</main>
<?php
	return ob_get_clean();
};
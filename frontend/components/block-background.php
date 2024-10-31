<?php
/**
 * Block background.
 *
 * @package   One Page Blocks
 * @author    Helder Vilela from Pixelthrone
 * @link      support@pixelthrone.com
 * @license   GPL-3.0
 */
use Pixelthrone\OnePage_Blocks\Utils;

$background = json_decode($background);
//var_dump($image);
//var_dump($background);
?>
<div data-component="block-background">
<?php
	switch ($background->type) {
		/**
		 * Color.
		 *
		 * @since   1.0.0
		 */
		case 'color':
		?>
			<div class="color-element" style="background-color: <?php echo $background->color ?>"></div>
		<?php
			echo '';
		break;
		/**
		 * Image.
		 *
		 * @since   1.0.0
		 */
		case 'image':
			if( ! empty($background->image) ):
			$image = $background->image->id ? wp_get_attachment_image_src( $background->image->id, 'opblocks_bg_image')[0] : $background->image->full;
		?>
				<div class="image-element">
					<span style="background-image: url('<?php echo esc_url($image); ?>')"></span>
				</div>
		<?php
			endif;
		break;
		/**
		 * Gallery.
		 *
		 * @since   1.0.0
		 */
		case 'gallery':
			?>
			<div class="gallery-element" data-component="slideshow-background">
				<div>
					<?php
						foreach ($background->gallery as $img ):
						$image = wp_get_attachment_image_src( $img->id, 'opblocks_bg_image');
					?>
					<span><img src="<?php echo esc_url($image[0]) ?>"/></span>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
		break;
		/**
		 * Video.
		 *
		 * @since   1.0.0
		 */
		case 'video':
			if( ! empty($background->video)  ):
				$image = $background->image->id ? wp_get_attachment_image_src( $background->image->id, 'opblocks_bg_image')[0] : '';
			?>
			<div class="video-element">
				<video playsinline autoPlay loop muted poster="<?php echo esc_url($image); ?>">
					<source src="<?php echo esc_url($background->video->url); ?>" type="video/mp4">
				</video>
			</div>
			<?php
			endif;
		break;
	}
?>
</div>

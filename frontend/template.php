<?php
use Pixelthrone\OnePage_Blocks\Plugin;
use Pixelthrone\OnePage_Blocks\Utils;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pageConfig = Plugin::get_frontend_settings();
$page = get_post($pageConfig->selected_page_id);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- Site info -->
<?php
	$tile = ! empty($pageConfig->seo->title) ? esc_html($pageConfig->seo->title) : $page->post_title;
	echo '<title>'.$tile.'</title>';
	echo ! empty($pageConfig->seo->description)           ? "\r\n\t".'<meta name="description" content="'.esc_attr($pageConfig->seo->description).'">' : '';
	echo ! empty($pageConfig->seo->keywords)              ? "\r\n\t".'<meta name="keywords" content="'.esc_attr($pageConfig->seo->keywords).'">' : '';
	echo ! empty($pageConfig->seo->author)                ? "\r\n\t".'<meta name="author" content="'.esc_attr($pageConfig->seo->author).'">' : '';
	echo ! empty($pageConfig->seo->copyright)             ? "\r\n\t".'<meta name="copyright" content="'.esc_attr($pageConfig->seo->copyright).'">' : '';
	echo ! empty($pageConfig->seo->serviceUnavailable)    ? "\r\n\t".'<meta name="robots" content="noindex,nofollow" />' : "\r\n\t".'<meta name="robots" content="index, follow" />';
	echo ! empty($pageConfig->seo->noCache)               ? "\r\n\t".'<meta http-equiv="cache-control" content="no-cache"/>' : '';
?>

<!-- Facebook -->
<?php
	echo ! empty($pageConfig->seo->facebook->title)           ? '<meta property="og:title" content="'.esc_attr($pageConfig->seo->facebook->title).'">' : '';
	echo ! empty($pageConfig->seo->facebook->description)     ? "\r\n\t".'<meta property="og:description" content="'.esc_attr($pageConfig->seo->facebook->title).'">' : '';
	echo ! empty($pageConfig->seo->facebook->cover->url)      ? "\r\n\t".'<meta property="og:image" content="'.esc_url($pageConfig->seo->facebook->cover->url).'">' : '';
?>

<!-- Twitter -->
<?php
	echo ! empty($pageConfig->seo->twitter->title)           ? '<meta property="twitter:title" content="'.esc_attr($pageConfig->seo->twitter->title).'">' : '';
	echo ! empty($pageConfig->seo->twitter->description)     ? "\r\n\t".'<meta property="twitter:description" content="'.esc_attr($pageConfig->seo->twitter->title).'">' : '';
	echo ! empty($pageConfig->seo->twitter->cover->url)      ? "\r\n\t".'<meta property="twitter:image" content="'.esc_url($pageConfig->seo->twitter->cover->url).'">' : '';
?>

<!-- Icons -->
<?php
	echo ! empty($pageConfig->lookandfeel->favicon->url)           ? '<link rel="icon" href="'.esc_url($pageConfig->lookandfeel->favicon->url).'">' : '';
?>

<!-- LOAD MAIN STYLE -->
<link rel="stylesheet" href="<?php echo Plugin::get_plugin_url('/public/dist/blocks.style.bundle.css') ?>">
<link rel="stylesheet" href="<?php echo Plugin::get_plugin_url('/public/dist/frontend.bundle.css') ?>">

</head>

<body   data-nonce="<?php echo wp_create_nonce( 'wp_rest' ); ?>"
		data-restURL="<?php echo esc_url_raw( rest_url() ); ?>"
		>

<?php
/**
 * Page Content
 *
 * @since    1.0.0
 */
echo do_blocks($page->post_content);

/**
 * Cookie Notice
 *
 * @since    1.0.0
 */
if( $pageConfig->settings->cookieNotice ) {
	$notalone = false;
	if ( opblocks_fs()->is_plan__premium_only('starter') ) {
		$notalone = $pageConfig->settings->passwordProtected && ! empty($pageConfig->settings->password) ? true : false;
	}
	Utils\component('cookie-notice', ['notalone' => $notalone] );
}

/**
 * Global site tag (gtag.js) - Google Analytics
 *
 * @since    1.0.0
 */
if( ! empty($pageConfig->settings->googleAnalytics) ):
?>
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-54038527-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', '<?php echo esc_html($pageConfig->settings->googleAnalytics); ?>');
	</script>
<?php
endif;

if ( opblocks_fs()->is_plan__premium_only('professional') ) {
	/**
	 * Others Analytics
	 *
	 * @since    1.0.0
	 */
	if ( ! empty( $pageConfig->settings->othersAnalytics ) ) {
		echo $pageConfig->settings->othersAnalytics;
	}
}

if ( opblocks_fs()->is_plan__premium_only('professional') ) {
	/**
	 * Custom JS
	 *
	 * @since    1.0.0
	 */
	if ( ! empty( $pageConfig->settings->JSCode ) ) {
		echo $pageConfig->settings->JSCode;
	}
}

/**
 * Print blocks CSS
 *
 * @since    1.0.0
 */
if ( ! empty( Utils\page_css('get') ) ) {
	$pageCSS = implode(Utils\page_css('get'), '');
	echo "<style id='blocks-css-compiled'>{$pageCSS}</style>";
}

/**
 * Load google fonts
 *
 * @since    1.0.0
 */
$pageFonts = Utils\page_fonts('get');
if ( ! empty( $pageFonts ) ) {
	foreach( $pageFonts as $font => $variants ) {
		$font = str_replace(" ", "+", $font);
		$variants = implode($variants, ',');
		echo "<link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family={$font}:{$variants}\" id=\"font-preview-acme\">";
	}
}

if ( opblocks_fs()->is_plan__premium_only('professional') ) {
	/**
	 * Custom CSS
	 *
	 * @since    1.0.0
	 */
	if ( ! empty( $pageConfig->settings->CSSCode ) ) {
		echo "<style>{$pageConfig->settings->CSSCode}</style>";
	}
}
?>

<!-- Scripts -->
<script src="<?php echo Plugin::get_plugin_url('/public/dist/frontend.bundle.js'); ?>"></script>

</body>
</html>
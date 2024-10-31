<?php
use Pixelthrone\OnePage_Blocks\Plugin;

return [
	'titleText'       => [
		'type'    => 'string',
		'default' => 'Hi! <strong>I\'m Helder</strong> and I\'m a <strong>Senior Product Designer</strong> who’s passionate about solving problems through design, and seeing how those solutions influence the world we live in.'
	],
	'subtitleText'   => [
		'type'    => 'string',
		'default' => 'Feel free to <a href="#">reach out</a>, check me on <a href="#">Dribbble</a> or find me on <a href="#">Twitter</a>.'
	],
	'align'           => [
		'type'    => 'string',
		'default' => 'full'
	],
	'overlayColor'    => [
		'type'    => 'string',
		'default' => '#ffffff'
	],
	'overlayOpacity'  => [
		'type'    => 'number',
		'default' => 0
	],
	'textColor'       => [
		'type'    => 'string',
		'default' => '#2b2b2b'
	],
	'linkColor'       => [
		'type'    => 'string',
		'default' => '#eb7c57'
	],
	'titleTypo'       => [
		'type'    => 'string',
		'default' => '{"fontfamily":"Open Sans","variants":["300","300italic","regular","italic","600","600italic","700","700italic","800","800italic"],"desktop":{"fontweight":"regular","fontsize":45,"lineheight":130,"letterspacing":0},"mobile":{"fontweight":"300","fontsize":27,"lineheight":135,"letterspacing":0}}',
	],
	'bodyTypo'        => [
		'type'    => 'string',
		'default' => '{"fontfamily":"Open Sans","variants":["300","300italic","regular","italic","600","600italic","700","700italic","800","800italic"],"desktop":{"fontweight":"regular","fontsize":20,"lineheight":130,"letterspacing":0},"mobile":{"fontweight":"300","fontsize":16,"lineheight":150,"letterspacing":0}}',
	],
	'contentMaxWidth' => [
		'type' => 'number',
		'default' => 1170,
	],
	'background'      => [
		'type'    => 'string',
		'default' => json_encode( [
			                          'type'    => 'color',
			                          'image'   => [],
			                          'gallery' => [],
			                          'video'   => [],
			                          'color'   => '#ffffff'
		                          ] ),
	]
];
<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist.css',
	'js' => 'dist.js',
	'rel' => [
		'main.polyfill.core',
		'ui.vue3',
	],
	'skip_core' => true,
];

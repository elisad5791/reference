<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/application.css',
	'js' => 'dist/application.js',
	'rel' => [
		'ui.vue3',
		'main.core',
	],
	'skip_core' => false,
];

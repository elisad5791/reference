<?php
defined('B_PROLOG_INCLUDED') && (B_PROLOG_INCLUDED === true) or die();

$aMenu = [
    'parent_menu' => 'global_menu_services',
    'sort' => 1,
    'text' => 'Тестовый модуль',
    'items_id' => 'menu_d7',
    'icon' => 'form_menu_icon',
    'items' => []
];

$aMenu['items'][] = [
    'text' => 'Страница модуля',
    'url' => 'elisad.php?lang=' . LANGUAGE_ID
];


$aMenu['items'][] = [
    'text' => 'Настройки модуля',
    'url' => 'settings.php?lang=ru&mid=elisad.d7'
];

return $aMenu;
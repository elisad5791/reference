<?php
defined('B_PROLOG_INCLUDED') && (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$aMenu = [
    'parent_menu' => 'global_menu_services',
    'sort' => 1,
    'text' => 'Модули Elisad',
    'items_id' => 'menu_elisad',
    'icon' => 'form_menu_icon',
    'items' => []
];

$aMenu['items'][] = [
    'text' => 'Очиска папки',
    'url' => 'settings.php?lang=ru&mid=elisad.clean'
];

return $aMenu;

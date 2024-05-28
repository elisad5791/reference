<?php
use Bitrix\Main\UI\Extension; 
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

Extension::load(['ui.design-tokens', 'lists', 'ui.buttons']);

$gridParams = [
    'GRID_ID' => $arResult['gridId'],
    'COLUMNS' => $arResult['columns'],
    'ROWS' => $arResult['rows'],
    'AJAX_MODE' => 'Y',
    'AJAX_OPTION_JUMP' => 'N',
    'AJAX_OPTION_HISTORY' => 'N',
    'SHOW_ROW_CHECKBOXES' => false,
    'SHOW_GRID_SETTINGS_MENU' => false,
    'SHOW_SELECTED_COUNTER' => false,
    'TOTAL_ROWS_COUNT' => $arResult['total']
];
$APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', $gridParams);
?>
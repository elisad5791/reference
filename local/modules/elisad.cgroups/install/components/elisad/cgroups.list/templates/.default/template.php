<?php

use Bitrix\Main\UI\Extension;

Extension::load('ui.label');

$params = [
    'GRID_ID' => $arResult['gridId'],
    'HEADERS' => $arResult['headers'],
    'ROWS' => $arResult['rows'],
    'SORT' => $arResult['sort'],
    'FILTER' => $arResult['filter'],
    'TOTAL_ROWS_COUNT' => $arResult['total'],
    'PAGINATION' => $arResult['pagination'],
    'IS_EXTERNAL_FILTER' => false,
    'ENABLE_LIVE_SEARCH' => false,
    'DISABLE_SEARCH' => true,
    'AJAX_ID' => '',
    'AJAX_OPTION_JUMP' => 'N',
    'AJAX_OPTION_HISTORY' => 'N',
    'AJAX_LOADER' => null
];
$comp = $this->getComponent();
$settings = ['HIDE_ICONS' => 'Y'];
$APPLICATION->IncludeComponent('bitrix:crm.interface.grid', 'titleflex', $params, $comp, $settings);
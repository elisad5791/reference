<?php
$params = [
    'ID' => 'CGROUPS',
    'ACTIVE_ITEM_ID' => 'CGROUPS',
];
$APPLICATION->IncludeComponent('bitrix:crm.control_panel', '', $params, $component);

$settings = ['HIDE_ICONS' => 'Y'];
$template = $arParams['SEF_URL_TEMPLATES']['details'];
$link = CComponentEngine::makePathFromTemplate($template, ['CGROUP_ID' => 0]);
$button = ['TEXT' => 'Редактировать', 'TITLE' => 'Редактировать', 'LINK' => $link, 'ICON' => 'btn-edit'];
$params = ['TOOLBAR_ID' => 'CGROUPS_TOOLBAR', 'BUTTONS' => [$button]];
$APPLICATION->IncludeComponent('bitrix:crm.interface.toolbar', 'title', $params, $component, $settings);

$params = ['CGROUP_ID' => $arResult['VARIABLES']['CGROUP_ID']];
$APPLICATION->IncludeComponent('elisad:cgroups.show', '', $params, $component);
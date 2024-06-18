<?php
$APPLICATION->SetTitle('Группы контактов');

$params = [
    'ID' => 'CGROUPS',
    'ACTIVE_ITEM_ID' => 'CGROUPS',
];
$APPLICATION->IncludeComponent('bitrix:crm.control_panel', '', $params, $component);

$settings = ['HIDE_ICONS' => 'Y'];
$template = $arParams['SEF_URL_TEMPLATES']['edit'];
$link = CComponentEngine::makePathFromTemplate($template, ['CGROUP_ID' => 0]);
$button = ['TEXT' => 'Создать', 'TITLE' => 'Создать', 'LINK' => $link, 'ICON' => 'btn-add'];
$params = ['TOOLBAR_ID' => 'CGROUPS_TOOLBAR', 'BUTTONS' => [$button]];
$APPLICATION->IncludeComponent('bitrix:crm.interface.toolbar', 'title', $params, $component, $settings);

$APPLICATION->IncludeComponent('elisad:cgroups.list', '', $arParams, $component);
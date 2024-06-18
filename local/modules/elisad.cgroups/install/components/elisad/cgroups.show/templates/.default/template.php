<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\UI\Extension;

$APPLICATION->SetTitle($arResult['TITLE']);

Loader::includeModule('crm');
Extension::load('ui.label');

$arg1 = $arResult['CGROUP']['ASSIGNED_BY_ID'];
$arg2 = CSite::GetNameFormat();
$arg3 = Option::get('intranet', 'path_user', '', SITE_ID) . '/';
$id = $arResult['CGROUP']['ID'];
$name = $arResult['CGROUP']['NAME'];
$tags = explode(',', $arResult['CGROUP']['INTERESTS']);
$interests = '';
foreach ($tags as $tag) {
    $interests .= '<span class="ui-label ui-label-tag-secondary ui-label-fill">' . $tag . '</span>';
}
$assigned = CCrmViewHelper::PrepareFormResponsible($arg1, $arg2, $arg3);
$fields = [
    ['id' => 'section_cgroup', 'name' => 'Группа контактов', 'type' => 'section', 'isTactile' => true],
    ['id' => 'ID', 'name' => 'ID', 'type' => 'label', 'value' => $id, 'isTactile' => true],
    ['id' => 'NAME', 'name' => 'Название', 'type' => 'label', 'value' => $name, 'isTactile' => true],
    ['id' => 'INTERESTS', 'name' => 'Интересы', 'type' => 'custom', 'value' => $interests, 'isTactile' => true],
    ['id' => 'ASSIGNED_BY', 'name' => 'Ответственный', 'type' => 'custom', 'value' => $assigned, 'isTactile' => true]
];

$title = 'Группа контактов';

$params = [
    'GRID_ID' => $arResult['GRID_ID'],
    'FORM_ID' => $arResult['FORM_ID'],
    'TACTILE_FORM_ID' => $arResult['TACTILE_FORM_ID'],
    'ENABLE_TACTILE_INTERFACE' => 'Y',
    'SHOW_SETTINGS' => 'Y',
    'DATA' => $arResult['CGROUP'],
    'TABS' => [['id' => 'tab_1', 'name' => $title, 'title' => $title, 'display' => false, 'fields' => $fields]]
];
$comp = $this->getComponent();
$settings = ['HIDE_ICONS' => 'Y'];
$APPLICATION->IncludeComponent('bitrix:crm.interface.form', 'show', $params, $comp, $settings);
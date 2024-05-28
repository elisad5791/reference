<?php
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$propTypes = [
    'S' => 'Строка',
    'F' => 'Файл',
    'E' => 'Привязка к элементам',
    'N' => 'Число'
];

$query = IblockTable::query();

$join = Join::on('this.ID', 'ref.IBLOCK_ID');
$field = new Reference('PROPS', PropertyTable::class, $join);
$query->registerRuntimeField($field);

$select = ['ID', 'CODE', 'API_CODE', 'NAME', 'PROPS_NAME' => 'PROPS.NAME', 'PROPS_TYPE' => 'PROPS.PROPERTY_TYPE'];
$query->setSelect($select);
$filter = ['IBLOCK_TYPE_ID' => 'lists'];
$query->setFilter($filter);
$lists = $query->fetchAll();

$arResult['gridId'] = 'LISTS_GRID';
$arResult['columns'] = [
    ['id' => 'ID', 'name' => '№', 'default' => true],
    ['id' => 'TITLE', 'name' => 'Название', 'default' => true],
    ['id' => 'CODE', 'name' => 'Символьный код', 'default' => true],
    ['id' => 'APICODE', 'name' => 'Символьный код API', 'default' => true],
    ['id' => 'PROPS', 'name' => 'Свойства', 'default' => true],
];

$rows = [];
foreach ($lists as $list) {
    $link = '/services/lists/' . $list['ID'] . '/view/0/?list_section_id=';
    $props = !empty($list['PROPS_NAME'])
        ? $list['PROPS_NAME'] . ' (' . $propTypes[$list['PROPS_TYPE']] . ')'
        : '';
    $rows[] = [
        'ID' => $list['ID'],
        'TITLE' => '<a href="' . $link . '">' . $list['NAME'] . '</a>',
        'CODE' => $list['CODE'],
        'APICODE' => $list['API_CODE'],
        'PROPS' => $props
    ];
}

$arResult['rows'] = [];
$key = 0;
foreach ($rows as $row) {
    $elements = array_filter($arResult['rows'], fn($item) => $item['columns']['ID'] == $row['ID']);

    if (empty($elements)) {
        $key += 1;
        $arResult['rows'][] = ['id' => $key, 'columns' => $row];
    } else {
        $ind = array_keys($elements)[0];
        $arResult['rows'][$ind]['columns']['PROPS'] .= '<br>' . $row['PROPS'];
    }
}

$arResult['total'] = count($arResult['rows']);
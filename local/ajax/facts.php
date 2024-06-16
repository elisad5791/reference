<?php
use Bitrix\Iblock\Elements\ElementFactsTable;
use Bitrix\Main\Loader;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

Loader::includeModule('iblock');
$select = ['ID', 'NAME', 'PREVIEW_TEXT'];
$facts = ElementFactsTable::getList(['select' => $select])->fetchAll();

$result = '<dl>';
foreach ($facts as $fact) {
    $result .= "<dt><strong>{$fact['NAME']}</strong></dt><dd><p>{$fact['PREVIEW_TEXT']}</p></dd>";
}
$result .= '</dl>';

echo json_encode($result);
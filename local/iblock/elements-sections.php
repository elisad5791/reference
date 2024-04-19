<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Loader;
use Bitrix\Iblock\Elements\ElementClientsTable;  // символьный код api - clients

Loader::includeModule('iblock');

/*--- обычным способом можно получить только один раздел --------*/
$elementId = 2;
$select = ['ID', 'IBLOCK_SECTION'];
$filter = ['ID' => $elementId];
$element = ElementClientsTable::getList(['select' => $select, 'filter' => $filter])->fetchObject();
$section = $element->getIblockSection()->getName();
Debug::dump($section, 'section');
/*
section=string(13) "Раздел1"
*/

/*--- получение всех разделов ------------------------------------*/
$elementId = 2;
$select = ['ID', 'SECTIONS'];
$filter = ['ID' => $elementId];
$element = ElementClientsTable::getList(['select' => $select, 'filter' => $filter])->fetchObject();
$sections = $element->getSections()->getAll();
foreach ($sections as $section) {
    Debug::dump($section->getId(), 'id');
    Debug::dump($section->getName(), 'name');
}
/*
id=int(15)
name=string(13) "Раздел1"
id=int(16)
name=string(13) "Раздел2"
*/
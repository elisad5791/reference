<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Loader;
use Bitrix\Iblock\Elements\ElementClientsTable;  // символьный код api - clients

Loader::includeModule('iblock');

/*--- получение одного элемента -------------------------*/
$elementId = 1;
$select = ['ID', 'NAME', 'SORT', 'PHONE_' => 'PHONE', 'PERSON_' => 'PERSON'];
$element = ElementClientsTable::getByPrimary($elementId, ['select' => $select])->fetchObject();
Debug::dump($element->getId(), 'id');
Debug::dump($element->getName(), 'name');
Debug::dump($element->getSort(), 'sort');
/*
id=int(1)
name=string(21) "ООО Копейка"
sort=int(500)
*/

/*--- получение коллекции элементов -----------------------*/
$elementId = 1;
$select = ['ID', 'NAME', 'SORT', 'PHONE_' => 'PHONE', 'PERSON_' => 'PERSON'];
$filter = ['ID' => $elementId];
$elements = ElementClientsTable::getList(['select' => $select, 'filter' => $filter])->fetchCollection();

foreach ($elements as $element) {
    Debug::dump($element->getId(), 'id');
    Debug::dump($element->getName(), 'name');
    Debug::dump($element->getSort(), 'sort');
}
/*
id=int(1)
name=string(21) "ООО Копейка"
sort=int(500)
*/
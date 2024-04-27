<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Iblock\Elements\ElementCitiesTable;

$newElement = ElementCitiesTable::createObject();
$newElement->setName('Пермь');
$newElement->setCountryId(59);  // пользовательское свойство
$newElement->save();

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
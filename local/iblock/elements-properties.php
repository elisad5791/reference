<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Loader;
use Bitrix\Iblock\Elements\ElementAutoTable;  // символьный код api - auto

Loader::includeModule('iblock');

/*--- свойство типа строка или число --------------------------------------*/
$elementId = 33;
$select = ['ID', 'MODEL'];
$element = ElementAutoTable::getByPrimary($elementId, ['select' => $select])->fetchObject();
$model = $element->get('MODEL')->getValue();
Debug::dump($model, 'model');
/*
model=string(6) "Audi10"
*/

/*--- множественное поле --------------------------------------------------*/
$elementId = 33;
$select = ['ID', 'PRODUCT_TYPES'];
$element = ElementAutoTable::getByPrimary($elementId, ['select' => $select])->fetchObject();
$types = $element->getProductTypes()->getAll();
foreach ($types as $val) {
    $type = $val->getValue();
    Debug::dump($type, 'type');
}
/*
type=string(4) "9840"
type=string(4) "8721"
*/

/*--- поле типа множественный файл ---------------------------------------*/
$elementId = 33;
$select = ['ID', 'IMAGES.FILE'];
$element = ElementAutoTable::getByPrimary($elementId, ['select' => $select])->fetchObject();
$images = $element->getImages()->getAll();
foreach ($images as $val) {
    $image = $val->getValue();
    $subdir = $val->getFile()->getSubdir();
    $filename = $val->getFile()->getFileName();
    Debug::dump($subdir, 'subdir');
    Debug::dump($filename, 'filename');
}
/*
subdir=string(43) "iblock/518/kazkvvwgo8ww6klv19r672ext3tjgei7"
filename=string(8) "sofa.jpg"
subdir=string(43) "iblock/eb2/60zvkola32k7oz5u7z38v9uv46cwc7es"
filename=string(9) "table.jpg"
*/

/*--- поле типа список ------------------------------------------------*/
$elementId = 33;
$select = ['ID', 'STATUS.ITEM'];
$element = ElementAutoTable::getByPrimary($elementId, ['select' => $select])->fetchObject();
$status = $element->getStatus()->getItem();
$value = $status->getValue();
$xml = $status->getXmlId();
Debug::dump($value, 'value');
Debug::dump($xml, 'xml');
/*
value=string(15) "В работе"
xml=string(4) "work"
*/

/*--- поле типа привязка к элементам множественное --------------------*/
$elementId = 33;
$select = ['ID', 'PROCEDURES.ELEMENT', 'PROCEDURES.ELEMENT.PRICE'];
$element = ElementAutoTable::getByPrimary($elementId, ['select' => $select])->fetchObject();
$procedures = $element->getProcedures()->getAll();
foreach ($procedures as $procedure) {
    $elem = $procedure->getElement();
    $name = $elem->getName();
    $price = $elem->getPrice()->getValue();
    Debug::dump($name);
    Debug::dump($price);
}
/*
string(22) "Диагностика"
string(4) "1000"
string(31) "Коррекция"
string(4) "2000"
*/
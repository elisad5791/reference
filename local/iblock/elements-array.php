<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Loader;
use Bitrix\Iblock\Elements\ElementClientsTable;  // символьный код api - clients

Loader::includeModule('iblock');

/*--- получение одного элемента ----------------------------*/
$elementId = 1;
$select = ['ID', 'NAME', 'SORT', 'PHONE_' => 'PHONE', 'PERSON_' => 'PERSON'];
$element = ElementClientsTable::getByPrimary($elementId, ['select' => $select])->fetch();
Debug::dump($element);
/*
array(11) {
  ["ID"]=>
  string(1) "1"
  ["NAME"]=>
  string(21) "ООО Копейка"
  ["SORT"]=>
  string(3) "500"
  ["PHONE_ID"]=>
  string(1) "2"
  ["PHONE_IBLOCK_ELEMENT_ID"]=>
  string(1) "1"
  ["PHONE_IBLOCK_PROPERTY_ID"]=>
  string(2) "14"
  ["PHONE_VALUE"]=>
  string(6) "556677"
  ["PERSON_ID"]=>
  string(1) "1"
  ["PERSON_IBLOCK_ELEMENT_ID"]=>
  string(1) "1"
  ["PERSON_IBLOCK_PROPERTY_ID"]=>
  string(2) "13"
  ["PERSON_VALUE"]=>
  string(44) "Иванов Василий Петрович"
}
*/

/*--- получение списка элементов -----------------------------------------*/
$select = ['ID', 'NAME', 'SORT', 'PHONE_' => 'PHONE', 'PERSON_' => 'PERSON'];;
$filter = ['=ACTIVE' => 'Y'];
$elements = ElementClientsTable::getList(['select' => $select, 'filter' => $filter])->fetchAll();
Debug::dump($elements);
/*
array(2) {
  [0]=>
  array(11) {
    ["ID"]=>
    string(1) "1"
    ["NAME"]=>
    string(21) "ООО Копейка"
    ["SORT"]=>
    string(3) "500"
    ["PHONE_ID"]=>
    string(1) "2"
    ["PHONE_IBLOCK_ELEMENT_ID"]=>
    string(1) "1"
    ["PHONE_IBLOCK_PROPERTY_ID"]=>
    string(2) "14"
    ["PHONE_VALUE"]=>
    string(6) "556677"
    ["PERSON_ID"]=>
    string(1) "1"
    ["PERSON_IBLOCK_ELEMENT_ID"]=>
    string(1) "1"
    ["PERSON_IBLOCK_PROPERTY_ID"]=>
    string(2) "13"
    ["PERSON_VALUE"]=>
    string(44) "Иванов Василий Петрович"
  }
  [1]=>
  array(11) {
    ["ID"]=>
    string(1) "2"
    ["NAME"]=>
    string(18) "ЗАО Зайцы+"
    ["SORT"]=>
    string(3) "500"
    ["PHONE_ID"]=>
    string(1) "4"
    ["PHONE_IBLOCK_ELEMENT_ID"]=>
    string(1) "2"
    ["PHONE_IBLOCK_PROPERTY_ID"]=>
    string(2) "14"
    ["PHONE_VALUE"]=>
    string(6) "557766"
    ["PERSON_ID"]=>
    string(1) "3"
    ["PERSON_IBLOCK_ELEMENT_ID"]=>
    string(1) "2"
    ["PERSON_IBLOCK_PROPERTY_ID"]=>
    string(2) "13"
    ["PERSON_VALUE"]=>
    string(44) "Лисицин Вольф Федерович"
  }
}
*/
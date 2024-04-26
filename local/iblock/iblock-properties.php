<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Iblock\PropertyTable;

/*--- получение пользовательских свойств ИБ --------------*/
$iblockId = 26;
$select =  ['ID', 'NAME', 'CODE', 'PROPERTY_TYPE'];
$filter = ['IBLOCK_ID' => $iblockId];
$result = PropertyTable::getList(['select' => $select, 'filter' => $filter])->fetchAll();
deb($result);
/*
array(3) {
  [0]=>
  array(4) {
    ["ID"]=>
    string(2) "88"
    ["NAME"]=>
    string(25) "График работы"
    ["CODE"]=>
    string(8) "SCHEDULE"
    ["PROPERTY_TYPE"]=>
    string(1) "S"
  }
  [1]=>
  array(4) {
    ["ID"]=>
    string(2) "89"
    ["NAME"]=>
    string(20) "Фотография"
    ["CODE"]=>
    string(5) "PHOTO"
    ["PROPERTY_TYPE"]=>
    string(1) "F"
  }
  [2]=>
  array(4) {
    ["ID"]=>
    string(2) "90"
    ["NAME"]=>
    string(18) "Процедуры"
    ["CODE"]=>
    string(10) "PROCEDURES"
    ["PROPERTY_TYPE"]=>
    string(1) "E"
  }
}
*/

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
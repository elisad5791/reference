<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Iblock\ElementTable;

$iblockId = 26;

/*--- получение элементов ИБ в старом ядре ------------------*/
$sort = ['ID' => 'DESC'];
$filter = ['IBLOCK_ID' => $iblockId];
$select = ['ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_SCHEDULE'];
$res = CIBlockElement::getList($sort, $filter, false, false, $select);
$elements = [];
while ($element = $res->Fetch()) {
    $elements[] = $element;
}
deb($elements);

/*
array(3) {
  [0]=>
  array(5) {
    ["ID"]=>
    string(2) "52"
    ["IBLOCK_ID"]=>
    string(2) "26"
    ["NAME"]=>
    string(46) "Петрова Ольга Евгеньевна"
    ["PROPERTY_SCHEDULE_VALUE"]=>
    string(16) "пн-пт, 15-20"
    ["PROPERTY_SCHEDULE_VALUE_ID"]=>
    string(3) "137"
  }
  [1]=>
  array(5) {
    ["ID"]=>
    string(2) "48"
    ["IBLOCK_ID"]=>
    string(2) "26"
    ["NAME"]=>
    string(48) "Комарова Мария Викторовна"
    ["PROPERTY_SCHEDULE_VALUE"]=>
    string(16) "пн-пт, 15-20"
    ["PROPERTY_SCHEDULE_VALUE_ID"]=>
    string(3) "124"
  }
  [2]=>
  array(5) {
    ["ID"]=>
    string(2) "47"
    ["IBLOCK_ID"]=>
    string(2) "26"
    ["NAME"]=>
    string(52) "Великанов Дмитрий Андреевич"
    ["PROPERTY_SCHEDULE_VALUE"]=>
    string(15) "пн-ср, 8-14"
    ["PROPERTY_SCHEDULE_VALUE_ID"]=>
    string(3) "119"
  }
}
*/

/*--- получение элементов ИБ в новом ядре, но только стандартные поля через общую таблицу -----*/
$filter = ['IBLOCK_ID' => $iblockId];
$select = ['ID', 'IBLOCK_ID', 'NAME'];
$elements = ElementTable::getList(['select' => $select, 'filter' => $filter])->fetchAll();
deb($elements);
/*
array(3) {
  [0]=>
  array(3) {
    ["ID"]=>
    string(2) "44"
    ["IBLOCK_ID"]=>
    string(2) "26"
    ["NAME"]=>
    string(46) "Михалков Петр Николаевич"
  }
  [1]=>
  array(3) {
    ["ID"]=>
    string(2) "45"
    ["IBLOCK_ID"]=>
    string(2) "26"
    ["NAME"]=>
    string(52) "Семенов Александр Сергеевич"
  }
  [2]=>
  array(3) {
    ["ID"]=>
    string(2) "46"
    ["IBLOCK_ID"]=>
    string(2) "26"
    ["NAME"]=>
    string(54) "Атяшева Наталья Владимировна"
  }
}
*/

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
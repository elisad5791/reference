<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Iblock\SectionTable;
use Bitrix\Iblock\Model\Section;

$iblockId = 21;

/*--- получение корневых разделов ИБ -------------------------*/
$select = ['ID', 'NAME', 'CODE'];
$filter = ['IBLOCK_ID' => $iblockId, 'DEPTH_LEVEL' => 1];
$result = SectionTable::getList(['select' => $select, 'filter' => $filter])->fetchAll();
deb($result);
/*
array(2) {
  [0]=>
  array(3) {
    ["ID"]=>
    string(2) "15"
    ["NAME"]=>
    string(10) "Новые"
    ["CODE"]=>
    string(3) "NEW"
  }
  [1]=>
  array(3) {
    ["ID"]=>
    string(2) "16"
    ["NAME"]=>
    string(22) "Проверенные"
    ["CODE"]=>
    string(3) "OLD"
  }
}
*/

/*--- получение всех разделов ИБ ----------------------------------*/
$select = ['ID', 'NAME', 'DEPTH_LEVEL'];
$filter = ['IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y'];
$result = SectionTable::getList(['select' => $select, 'filter' => $filter])->fetchAll();
deb($result);
/*
array(4) {
  [0]=>
  array(3) {
    ["ID"]=>
    string(2) "15"
    ["NAME"]=>
    string(10) "Новые"
    ["DEPTH_LEVEL"]=>
    string(1) "1"
  }
  [1]=>
  array(3) {
    ["ID"]=>
    string(2) "16"
    ["NAME"]=>
    string(22) "Проверенные"
    ["DEPTH_LEVEL"]=>
    string(1) "1"
  }
  [2]=>
  array(3) {
    ["ID"]=>
    string(2) "17"
    ["NAME"]=>
    string(15) "В работе"
    ["DEPTH_LEVEL"]=>
    string(1) "2"
  }
  [3]=>
  array(3) {
    ["ID"]=>
    string(2) "18"
    ["NAME"]=>
    string(18) "Возможные"
    ["DEPTH_LEVEL"]=>
    string(1) "2"
  }
}
*/

/*--- получение разделов ИБ вместе с пользовательскими полями ----------*/
$class = Section::compileEntityByIblock($iblockId);
$select = ['ID', 'NAME', 'CODE', 'UF_COMMENT'];
$filter = ['IBLOCK_ID' => $iblockId];
$result = $class::getList(['select' => $select, 'filter' => $filter])->fetchAll();
deb($result);
/*
array(4) {
  [0]=>
  array(4) {
    ["ID"]=>
    string(2) "15"
    ["NAME"]=>
    string(10) "Новые"
    ["CODE"]=>
    string(3) "NEW"
    ["UF_COMMENT"]=>
    string(54) "Требуют повышенного внимания"
  }
  [1]=>
  array(4) {
    ["ID"]=>
    string(2) "16"
    ["NAME"]=>
    string(22) "Проверенные"
    ["CODE"]=>
    string(3) "OLD"
    ["UF_COMMENT"]=>
    string(27) "Обычный статус"
  }
  [2]=>
  array(4) {
    ["ID"]=>
    string(2) "17"
    ["NAME"]=>
    string(15) "В работе"
    ["CODE"]=>
    string(4) "WORK"
    ["UF_COMMENT"]=>
    string(21) "Не упустить"
  }
  [3]=>
  array(4) {
    ["ID"]=>
    string(2) "18"
    ["NAME"]=>
    string(18) "Возможные"
    ["CODE"]=>
    string(8) "POSSIBLE"
    ["UF_COMMENT"]=>
    string(40) "Нужна активная работа"
  }
}
*/

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
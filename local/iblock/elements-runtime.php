<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Iblock\Elements\ElementCitiesTable;
use Bitrix\Iblock\Elements\ElementCountriesTable;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

/*--- получение элементов из связанных ИБ через runtime-поля --------*/
$query = ElementCitiesTable::query();

$class = ElementCountriesTable::class;
$join = Join::on('this.COUNTRY_ID.VALUE', 'ref.ID');
$field = new Reference('COUNTRY', $class, $join);
$query->registerRuntimeField($field);

$select =  ['ID', 'NAME', 'COUNTRY_NAME' => 'COUNTRY.NAME'];
$query->setSelect($select);

$result = $query->fetchAll();
deb($result);
/*
array(4) {
  [0]=>
  array(3) {
    ["ID"]=>
    string(2) "53"
    ["NAME"]=>
    string(10) "Париж"
    ["COUNTRY_NAME"]=>
    string(14) "Франция"
  }
  [1]=>
  array(3) {
    ["ID"]=>
    string(2) "54"
    ["NAME"]=>
    string(15) "Нью-Йорк"
    ["COUNTRY_NAME"]=>
    string(6) "США"
  }
  [2]=>
  array(3) {
    ["ID"]=>
    string(2) "55"
    ["NAME"]=>
    string(12) "Бостон"
    ["COUNTRY_NAME"]=>
    string(6) "США"
  }
  [3]=>
  array(3) {
    ["ID"]=>
    string(2) "56"
    ["NAME"]=>
    string(23) "Лос-Анджелес"
    ["COUNTRY_NAME"]=>
    string(6) "США"
  }
}
*/

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
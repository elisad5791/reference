<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Type\Date;
use Bitrix\Main\DB\SqlExpression;

/*--- добавление записи -------------------------------------*/
$fields = [
    'ISBN' => '978-0321127426',
    'TITLE' => 'Patterns of Enterprise Application Architecture',
    'PUBLISH_DATE' => new Date('2002-11-16', 'Y-m-d')
];
$result = BookTable::add($fields);
if ($result->isSuccess()) {
    $id = $result->getId();
} else {
    $errors = $result->getErrorMessages();
}
Debug::dump($id, 'id');
Debug::dump($errors, 'errors');
/*
id=int(1)
errors=NULL
*/

/*--- обновление записи -------------------------------------*/
$id = 1;
$fields = ['PUBLISH_DATE' => new Date('2002-11-15', 'Y-m-d')];
$result = BookTable::update($id, $fields);
if ($result->isSuccess()) {
    $rows = $result->getAffectedRowsCount();
    Debug::dump($rows, 'rows');
}
/*
rows=int(1)
*/

/*--- удаление записи -----------------------------------------*/
$id = 1;
$result = BookTable::delete($id);
if ($result->isSuccess()) {
    Debug::dump('success');
}
/*
string(7) "success"
*/

/*--- обновление поля через sql-выражение ---------------------*/
$id = 1;
$readersCount = 3;
$fields = ['READERS_COUNT' => new SqlExpression('?# + ?i', 'READERS_COUNT', $readersCount)];
$result = BookTable::update($id, $fields);
if ($result->isSuccess()) {
    $rows = $result->getAffectedRowsCount();
    Debug::dump($rows, 'rows');
}
/*
rows=int(1)
*/

/*--- обновление поля, которое нельзя обновлять ----------------*/
$id = 1;
$fields = ['ISBN' => '1234567891234'];
$result = BookTable::update($id, $fields);
if ($result->isSuccess()) {
    $rows = $result->getAffectedRowsCount();
} else {
    $errors = $result->getErrorMessages();
}
Debug::dump($rows, 'rows');
Debug::dump($errors, 'errors');
/*
rows=NULL
errors:
array(1) {
  [0]=>
  string(50) "Невозможно обновить запись"
}
*/
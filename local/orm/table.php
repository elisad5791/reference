<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Type\Date;

/*--- sql для создания таблицы в БД --------------------------*/
$sql = BookTable::getEntity()->compileDbTableStructureDump();
Debug::dump($sql);
/*
CREATE TABLE `my_book` (
    `ID` int NOT NULL AUTO_INCREMENT, 
    `ISBN` varchar(255) NOT NULL, 
    `TITLE` varchar(255) NOT NULL, 
    `PUBLISH_DATE` date NOT NULL, 
    PRIMARY KEY(`ID`)
)
*/

/*--- добавление записи -------------------------------------*/
$fields = [
    'ISBN' => '978-0321127426',
    'TITLE' => 'Patterns of Enterprise Application Architecture',
    'PUBLISH_DATE' => new Date('2002-11-16', 'Y-m-d')
];
$result = BookTable::add($fields);
if ($result->isSuccess()) {
    $id = $result->getId();
}
Debug::dump($id, 'id');
/*
id=int(1)
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
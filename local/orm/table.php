<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

use Bitrix\Main\Diag\Debug;

/*--- sql для создания таблицы в БД --------------------------*/
$sql = BookTable::getEntity()->compileDbTableStructureDump();
Debug::dump($sql);
/*
CREATE TABLE `my_book` (
    `ID` int NOT NULL AUTO_INCREMENT, 
    `ISBN` varchar(255) NOT NULL, 
    `TITLE` varchar(255) NOT NULL, 
    `PUBLISH_DATE` date NOT NULL, 
    `READERS_COUNT` int NOT NULL, 
    PRIMARY KEY(`ID`)
)
*/
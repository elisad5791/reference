<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Main\Diag\Debug;

/*--- обновление объекта --------------------------------------*/
$id = 2;
$book = BookTable::getByPrimary($id)->fetchObject();
$book->setTitle('New title');
$title = $book->getTitle();
Debug::dump($title, 'title');
/*
title=string(9) "New title"
*/

$book->resetTitle();
$title = $book->getTitle();
Debug::dump($title, 'title');
/*
title=string(47) "Patterns of Enterprise Application Architecture"
*/

$book->setTitle('New title');
$book->save();

$book = BookTable::getByPrimary($id)->fetchObject();
$title = $book->getTitle();
Debug::dump($title, 'title');
/*
title=string(9) "New title"
*/

$book->unsetTitle();
$title = $book->getTitle();
Debug::dump($title, 'title');
/*
title=NULL
*/
$book->save();

/*--- создание объекта ------------------------------------------*/
$newBook = BookTable::createObject();
$newBook->setTitle('New title');
$newBook->setIsbn('1234567899515');
$newBook->save();

/*--- удаление объекта ------------------------------------------*/
$id = 4;
$book = BookTable::getByPrimary($id)->fetchObject();
$book->delete();

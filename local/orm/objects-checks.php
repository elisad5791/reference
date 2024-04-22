<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\ORM\Objectify\State;

$id = 1;
$book = BookTable::getByPrimary($id)->fetchObject();
$result = $book->isTitleFilled();
Debug::dump($result, 'result');
/*
result=bool(true)
*/

$book->setTitle('New title 1');
$result = $book->isTitleChanged();
Debug::dump($result, 'result');
/*
result=bool(true)
*/

$result = $book->hasTitle();
Debug::dump($result, 'result');
/*
result=bool(true)
*/

$newBook = BookTable::createObject();
$newBook->setTitle('New title');
$newBook->setIsbn('1234567899515');
$result = $newBook->state === State::RAW;
Debug::dump($result, 'result');
/*
result=bool(true)
*/

$newBook->save();
$result = $newBook->state === State::ACTUAL;
Debug::dump($result, 'result');
/*
result=bool(true)
*/

$newBook->setTitle('Another one title');
$result = $newBook->state === State::CHANGED;
Debug::dump($result, 'result');
/*
result=bool(true)
*/
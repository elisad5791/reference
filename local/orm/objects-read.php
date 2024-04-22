<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\ORM\Objectify\Values;

/*--- чтение поля объекта --------------------------------*/
$id = 1;
$book = BookTable::getByPrimary($id)->fetchObject();
$title = $book->getTitle();
Debug::dump($title, 'title');
/*
title=string(47) "Patterns of Enterprise Application Architecture"
*/

/*--- чтение измененного и актуального поля объекта ---------*/
$book->setTitle('New title');
$newTitle = $book->getTitle();
Debug::dump($newTitle, 'new-title');
/*
new-title=string(9) "New title"
*/
$actualTitle = $book->remindActualTitle();
Debug::dump($actualTitle, 'actual-title');
/*
actual-title=string(47) "Patterns of Enterprise Application Architecture"
*/

/*--- чтение первичного ключа объекта -----------------------*/
$primary = $book->primary;
Debug::dump($primary, 'primary');
/*
primary:
array(1) {
  ["ID"]=>
  int(1)
}
*/
$id = $book->getId();
Debug::dump($id, 'id');
/*
id=int(1)
*/

/*--- чтение всех полей объекта в виде массива ---------------*/
$values = $book->collectValues();
Debug::dump($values, 'values');
/*
values:
array(5) {
  ["ID"]=>
  int(1)
  ["ISBN"]=>
  string(14) "978-0321127426"
  ["TITLE"]=>
  string(9) "New title"              (!!!)
  ["PUBLISH_DATE"]=>
  object(Bitrix\Main\Type\Date)#1579 (1) {
    ["value":protected]=>
    object(DateTime)#1578 (3) {
      ["date"]=>
      string(26) "2002-11-16 00:00:00.000000"
      ["timezone_type"]=>
      int(3)
      ["timezone"]=>
      string(13) "Europe/Moscow"
    }
  }
  ["READERS_COUNT"]=>
  int(3)
}
*/

/*--- чтение всех актуальных полей объекта в виде массива ---------------*/
$values = $book->collectValues(Values::ACTUAL);
Debug::dump($values, 'values');
/*
values:
array(5) {
  ["ID"]=>
  int(1)
  ["ISBN"]=>
  string(14) "978-0321127426"
  ["TITLE"]=>
  string(9) "Patterns of Enterprise Application Architecture"     (!!!)
  ["PUBLISH_DATE"]=>
  object(Bitrix\Main\Type\Date)#1579 (1) {
    ["value":protected]=>
    object(DateTime)#1578 (3) {
      ["date"]=>
      string(26) "2002-11-16 00:00:00.000000"
      ["timezone_type"]=>
      int(3)
      ["timezone"]=>
      string(13) "Europe/Moscow"
    }
  }
  ["READERS_COUNT"]=>
  int(3)
}
*/

/*--- чтение измененных полей объекта в виде массива ---------------*/
$values = $book->collectValues(Values::CURRENT);
Debug::dump($values, 'values');
/*
values:
array(1) {
  ["TITLE"]=>
  string(9) "New title"
}
*/
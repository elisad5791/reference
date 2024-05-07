<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Context;
use Bitrix\Main\Type\Date;

$request = Context::getCurrent()->getRequest();
$heading = $request->get('heading');
$body = $request->get('body');
$authorId = $request->get('author');
$categoryId = $request->get('category');

Loader::includeModule('highloadblock');
$postsId = 2;
$hl = HighloadBlockTable::getById($postsId)->fetch();
$entity = HighloadBlockTable::compileEntity($hl);
$postsClass = $entity->getDataClass();

$fields = [
    'UF_HEADING' => $heading,
    'UF_BODY' => $body,
    'UF_DATE' => new Date(date('Y-m-d'), 'Y-m-d'),
    'UF_CATEGORY' => $categoryId,
    'UF_AUTHOR' => $authorId
];
$postsClass::add($fields);

LocalRedirect('/local/blog/editor.php?id=' . $authorId);


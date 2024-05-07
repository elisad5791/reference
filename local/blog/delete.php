<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Context;

$request = Context::getCurrent()->getRequest();
$postId = $request->get('id');

Loader::includeModule('highloadblock');
$postsId = 2;
$commentsId = 5;

$hl = HighloadBlockTable::getById($postsId)->fetch();
$entity = HighloadBlockTable::compileEntity($hl);
$postsClass = $entity->getDataClass();
$hl = HighloadBlockTable::getById($commentsId)->fetch();
$entity = HighloadBlockTable::compileEntity($hl);
$commentsClass = $entity->getDataClass();

$post = $postsClass::getList(['select' => ['UF_AUTHOR'], 'filter' => ['ID' => $postId]])->fetch();
$authorId = $post['UF_AUTHOR'];
$postsClass::delete($postId);

$comments = $commentsClass::getList(['select' => ['ID'], 'filter' => ['UF_POST' => $postId]])->fetchAll();
foreach ($comments as $comment) {
    $commentsClass::delete($comment['ID']);
}

LocalRedirect('/local/blog/editor.php?id=' . $authorId);
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Context;

$request = Context::getCurrent()->getRequest();

Loader::includeModule('highloadblock');
$postsId = 2;
$categoriesId = 3;

$hl = HighloadBlockTable::getById($postsId)->fetch();
$entity = HighloadBlockTable::compileEntity($hl);
$postsClass = $entity->getDataClass();
$hl = HighloadBlockTable::getById($categoriesId)->fetch();
$entity = HighloadBlockTable::compileEntity($hl);
$categoriesClass = $entity->getDataClass();

if ($request->isPost()) {
    $postId = $request->get('id');
    $heading = $request->get('heading');
    $body = $request->get('body');
    $authorId = $request->get('author');
    $categoryId = $request->get('category');
    $fields = [
        'UF_HEADING' => $heading,
        'UF_BODY' => $body,
        'UF_CATEGORY' => $categoryId
    ];
    $postsClass::update($postId, $fields);
    LocalRedirect('/local/blog/editor.php?id=' . $authorId);
}

$postId = $request->get('id');

$select = ['ID', 'UF_HEADING', 'UF_BODY', 'UF_CATEGORY', 'UF_AUTHOR'];
$filter = ['ID' => $postId];
$post = $postsClass::getList(['select' => $select, 'filter' => $filter])->fetch();

$select = ['ID', 'UF_TITLE'];
$categories = $categoriesClass::getList(['select' => $select])->fetchAll();
?>

<h1 style="margin-bottom:40px;">Редактирование статьи</h1>

<form action="" method="post" style="display:grid;grid-template-columns:auto 1fr;grid-gap:10px;margin-bottom:40px;">
    <input type="hidden" name="author" value="<?= $post['UF_AUTHOR'] ?>">
    <input type="hidden" name="id" value="<?= $post['ID'] ?>">
    <label for="heading">Название</label>
    <div class="ui-ctl ui-ctl-textbox">
        <input type="text" class="ui-ctl-element" name="heading" id="heading" value="<?= $post['UF_HEADING'] ?>">
    </div>
    <label for="body">Текст</label>
    <div class="ui-ctl ui-ctl-textarea">
        <textarea class="ui-ctl-element" name="body" id="body"><?= $post['UF_BODY'] ?></textarea>
    </div>
    <label for="category">Категория</label>
    <div class="ui-ctl ui-ctl-after-icon ui-ctl-dropdown">
        <div class="ui-ctl-after ui-ctl-icon-angle"></div>
        <select class="ui-ctl-element" name="category" id="category">
            <?php foreach ($categories as $category) { ?>
                <option value="<?= $category['ID'] ?>" <?= $post['UF_CATEGORY'] == $category['ID'] ? 'selected' : '' ?>>
                    <?= $category['UF_TITLE'] ?>
                </option>
            <?php } ?>
        </select>
    </div>
    <div style="margin-top:20px;grid-column:2/3;">
        <button type="submit" class="ui-btn ui-btn-primary ui-btn-icon-add">Сохранить</button>
        <a href="/local/blog/editor.php?id=<?= $post['UF_AUTHOR'] ?>" class="ui-btn ui-btn-primary">
            Отмена
        </a>
    </div>
</form>

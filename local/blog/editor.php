<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Context;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UI\Extension;

$request = Context::getCurrent()->getRequest();
$authorId = $request->get('id');

Extension::load('ui.buttons.icons');
Extension::load('ui.forms');
Loader::includeModule('highloadblock');
$authorsId = 4;
$postsId = 2;
$categoriesId = 3;

$hl = HighloadBlockTable::getById($authorsId)->fetch();
$entity = HighloadBlockTable::compileEntity($hl);
$authorsClass = $entity->getDataClass();
$hl = HighloadBlockTable::getById($postsId)->fetch();
$entity = HighloadBlockTable::compileEntity($hl);
$postsClass = $entity->getDataClass();
$hl = HighloadBlockTable::getById($categoriesId)->fetch();
$entity = HighloadBlockTable::compileEntity($hl);
$categoriesClass = $entity->getDataClass();

$query = $postsClass::query();

$join = Join::on('this.UF_CATEGORY', 'ref.ID');
$field = new Reference('CATEGORY', $categoriesClass, $join);
$query->registerRuntimeField($field);

$join = Join::on('this.UF_AUTHOR', 'ref.ID');
$field = new Reference('AUTHOR', $authorsClass, $join);
$query->registerRuntimeField($field);

$select = [
    'ID', 'UF_HEADING', 'UF_BODY', 'UF_DATE', 'UF_AUTHOR', 'UF_CATEGORY',
    'CATEGORY_ID' => 'CATEGORY.ID', 'CATEGORY_TITLE' => 'CATEGORY.UF_TITLE',
    'AUTHOR_ID' => 'AUTHOR.ID', 'AUTHOR_NAME' => 'AUTHOR.UF_NAME', 'AUTHOR_LASTNAME' => 'AUTHOR.UF_LASTNAME'
];
$query->setSelect($select);
$filter = ['UF_AUTHOR' => $authorId];
$query->setFilter($filter);
$posts = $query->fetchAll();

$select = ['ID', 'UF_TITLE'];
$categories = $categoriesClass::getList(['select' => $select])->fetchAll();
?>

<h1>Редакторская страница автора: <?= $posts[0]['AUTHOR_NAME'] ?> <?= $posts[0]['AUTHOR_LASTNAME'] ?></h1>

<h2>Новая статья</h2>
<form action="/local/blog/add.php" method="post"
      style="display:grid;grid-template-columns:auto 1fr;grid-gap:10px;margin-bottom:40px;">
    <input type="hidden" name="author" value="<?= $authorId ?>">
    <label for="heading">Название</label>
    <div class="ui-ctl ui-ctl-textbox">
        <input type="text" class="ui-ctl-element" name="heading" id="heading">
    </div>
    <label for="body">Текст</label>
    <div class="ui-ctl ui-ctl-textarea">
        <textarea class="ui-ctl-element" name="body" id="body"></textarea>
    </div>
    <label for="category">Категория</label>
    <div class="ui-ctl ui-ctl-after-icon ui-ctl-dropdown">
        <div class="ui-ctl-after ui-ctl-icon-angle"></div>
        <select class="ui-ctl-element" name="category" id="category">
            <?php foreach ($categories as $category) { ?>
                <option value="<?= $category['ID'] ?>"><?= $category['UF_TITLE'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div style="margin-top:20px;grid-column:2/3;">
        <button type="submit" class="ui-btn ui-btn-primary ui-btn-icon-add">Создать</button>
    </div>
</form>

<?php foreach ($posts as $post) { ?>
    <div style="margin-bottom:30px">
        <h2><a href="/local/blog/post.php?id=<?= $post['ID'] ?>"><?= $post['UF_HEADING'] ?></a></h2>
        <h5><?= $post['AUTHOR_NAME'] ?> <?= $post['AUTHOR_LASTNAME'] ?></h5>
        <h5>
            <a href="/local/blog/category.php?id=<?= $post['CATEGORY_ID'] ?>"><?= $post['CATEGORY_TITLE'] ?></a>,
            <?= $post['UF_DATE'] ?>
        </h5>
        <p style="width:50%;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            <?= $post['UF_BODY'] ?>
        </p>
        <p>
            <a href="/local/blog/edit.php?id=<?= $post['ID'] ?>">
                <strong>Редактировать</strong>
            </a>
            <a href="/local/blog/delete.php?id=<?= $post['ID'] ?>" style="margin-left:40px;">
                <strong>Удалить</strong>
            </a>
        </p>
    </div>
<?php } ?>

<p style="margin-top:50px">
    <a href="/local/blog/">Вернуться на главную &rarr;</a>
</p>
<p>
    <a href="/local/blog/author.php?id=<?= $posts[0]['AUTHOR_ID'] ?>">Вернуться к обычному списку &rarr;</a>
</p>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
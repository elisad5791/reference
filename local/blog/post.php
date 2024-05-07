<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Context;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

$request = Context::getCurrent()->getRequest();
$postId = $request->get('id');

Loader::includeModule('highloadblock');
$categoriesId = 3;
$postsId = 2;
$authorsId = 4;
$commentsId = 5;

$hl = HighloadBlockTable::getById($postsId)->fetch();
$entity = HighloadBlockTable::compileEntity($hl);
$postsClass = $entity->getDataClass();
$hl = HighloadBlockTable::getById($categoriesId)->fetch();
$entity = HighloadBlockTable::compileEntity($hl);
$categoriesClass = $entity->getDataClass();
$hl = HighloadBlockTable::getById($authorsId)->fetch();
$entity = HighloadBlockTable::compileEntity($hl);
$authorsClass = $entity->getDataClass();
$hl = HighloadBlockTable::getById($commentsId)->fetch();
$entity = HighloadBlockTable::compileEntity($hl);
$commentsClass = $entity->getDataClass();

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
$filter = ['ID' => $postId];
$query->setFilter($filter);
$post = $query->fetch();

$query = $commentsClass::query();

$join = Join::on('this.UF_AUTHOR', 'ref.ID');
$field = new Reference('AUTHOR', $authorsClass, $join);
$query->registerRuntimeField($field);

$select = ['ID', 'UF_TEXT', 'AUTHOR_NAME' => 'AUTHOR.UF_NAME', 'AUTHOR_LASTNAME' => 'AUTHOR.UF_LASTNAME'];
$query->setSelect($select);
$filter = ['UF_POST' => $postId];
$query->setFilter($filter);
$comments = $query->fetchAll();
?>

<h1><?= $post['UF_HEADING'] ?></h1>
    <h3>
        <a href="/local/blog/category.php?id=<?= $post['CATEGORY_ID'] ?>"><?= $post['CATEGORY_TITLE'] ?></a>,
        <?= $post['UF_DATE'] ?>
    </h3>
<h3>
    <a href="/local/blog/author.php?id=<?= $post['AUTHOR_ID'] ?>">
        <?= $post['AUTHOR_NAME'] ?> <?= $post['AUTHOR_LASTNAME'] ?>
    </a>
</h3>
<p style="width:75%"><?= $post['UF_BODY'] ?></p>

<h3>Комментарии</h3>
<?php foreach ($comments as $comment) { ?>
    <div style="margin-top:20px">
        <h4><?= $comment['AUTHOR_NAME'] ?> <?= $comment['AUTHOR_LASTNAME'] ?></h4>
        <p><?= $comment['UF_TEXT'] ?></p>
    </div>
<?php } ?>

<p style="margin-top:50px">
    <a href="/local/blog/">Вернуться на главную &rarr;</a>
</p>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
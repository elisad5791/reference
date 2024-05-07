<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Context;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

$request = Context::getCurrent()->getRequest();
$categoryId = $request->get('id');

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
$query->registerRuntimeField($field);$join = Join::on('this.UF_CATEGORY', 'ref.ID');

$join = Join::on('this.UF_AUTHOR', 'ref.ID');
$field = new Reference('AUTHOR', $authorsClass, $join);
$query->registerRuntimeField($field);

$select = [
    'ID', 'UF_HEADING', 'UF_BODY', 'UF_DATE', 'UF_AUTHOR', 'UF_CATEGORY',
    'CATEGORY_ID' => 'CATEGORY.ID', 'CATEGORY_TITLE' => 'CATEGORY.UF_TITLE',
    'AUTHOR_ID' => 'AUTHOR.ID', 'AUTHOR_NAME' => 'AUTHOR.UF_NAME', 'AUTHOR_LASTNAME' => 'AUTHOR.UF_LASTNAME'
];
$query->setSelect($select);
$filter = ['UF_CATEGORY' => $categoryId];
$query->setFilter($filter);
$posts = $query->fetchAll();
?>

<h1>Категория: <?= $posts[0]['CATEGORY_TITLE'] ?></h1>

<?php foreach ($posts as $post) { ?>
    <div style="margin-bottom:30px">
        <h4><a href="/local/blog/post.php?id=<?= $post['ID'] ?>"><?= $post['UF_HEADING'] ?></a></h4>
        <h6>
            <a href="/local/blog/author.php?id=<?= $post['AUTHOR_ID'] ?>">
                <?= $post['AUTHOR_NAME'] ?> <?= $post['AUTHOR_LASTNAME'] ?>
            </a>,
            <?= $post['CATEGORY_TITLE'] ?>,
            <?= $post['UF_DATE'] ?>
        </h6>
        <p style="width:50%;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            <?= $post['UF_BODY'] ?>
        </p>
    </div>
<?php } ?>

    <p style="margin-top:50px">
        <a href="/local/blog/">Вернуться на главную &rarr;</a>
    </p>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
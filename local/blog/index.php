<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

Loader::includeModule('highloadblock');
$categoriesId = 3;
$postsId = 2;
$authorsId = 4;

$hl = HighloadBlockTable::getById($authorsId)->fetch();
$entity = HighloadBlockTable::compileEntity($hl);
$authorsClass = $entity->getDataClass();
$hl = HighloadBlockTable::getById($postsId)->fetch();
$entity = HighloadBlockTable::compileEntity($hl);
$postsClass = $entity->getDataClass();
$hl = HighloadBlockTable::getById($categoriesId)->fetch();
$entity = HighloadBlockTable::compileEntity($hl);
$categoriesClass = $entity->getDataClass();

$select = ['ID', 'UF_TITLE'];
$categories = $categoriesClass::getList(['select' => $select])->fetchAll();

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
$query->setOrder(['UF_DATE' => 'DESC']);
$query->setLimit(3);
$posts = $query->fetchAll();
?>

<h1>Блог</h1>

<h3>Категории</h3>
<ul>
    <?php foreach ($categories as $category) { ?>
        <li>
            <a href="/local/blog/category.php?id=<?= $category['ID'] ?>">
                <?= $category['UF_TITLE'] ?>
            </a>
        </li>
    <?php } ?>
</ul>

<?php foreach ($posts as $post) { ?>
    <div style="margin-bottom:30px">
        <h4><a href="/local/blog/post.php?id=<?= $post['ID'] ?>"><?= $post['UF_HEADING'] ?></a></h4>
        <h6>
            <a href="/local/blog/author.php?id=<?= $post['AUTHOR_ID'] ?>">
                <?= $post['AUTHOR_NAME'] ?> <?= $post['AUTHOR_LASTNAME'] ?>
            </a>,
            <a href="/local/blog/category.php?id=<?= $post['CATEGORY_ID'] ?>">
                <?= $post['CATEGORY_TITLE'] ?>
            </a>,
            <?= $post['UF_DATE'] ?>
        </h6>
        <p style="width:50%;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            <?= $post['UF_BODY'] ?>
        </p>
    </div>
<?php } ?>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
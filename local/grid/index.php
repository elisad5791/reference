<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UI\Filter\Options;

Loader::includeModule('highloadblock');
$categoriesId = 3;
$postsId = 2;
$authorsId = 4;

$hl = HighloadBlockTable::getById($postsId)->fetch();
$entity = HighloadBlockTable::compileEntity($hl);
$postsClass = $entity->getDataClass();
$hl = HighloadBlockTable::getById($categoriesId)->fetch();
$entity = HighloadBlockTable::compileEntity($hl);
$categoriesClass = $entity->getDataClass();
$hl = HighloadBlockTable::getById($authorsId)->fetch();
$entity = HighloadBlockTable::compileEntity($hl);
$authorsClass = $entity->getDataClass();

$gridId = 'POSTS_GRID';
$filterId = 'POSTS_FILTER';

$filterOptions = new Options($filterId);
$filterFields = $filterOptions->getFilter([]);
$filter = [];
foreach ($filterFields as $key => $value) {
    if ($key == 'CATEGORY') {
        $filter = ['UF_CATEGORY' => $value];
    }
}

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
$query->setFilter($filter);
$posts = $query->fetchAll();

$columns = [
    ['id' => 'ID', 'name' => '№', 'default' => true],
    ['id' => 'HEADING', 'name' => 'Название', 'default' => true],
    ['id' => 'BODY', 'name' => 'Текст', 'default' => true],
    ['id' => 'DATE', 'name' => 'Дата создания', 'default' => true],
    ['id' => 'AUTHOR', 'name' => 'Автор', 'default' => true],
    ['id' => 'CATEGORY', 'name' => 'Категория', 'default' => true]
];

$rows = [];
foreach ($posts as $key => $post) {
    $cols = [
        'ID' => $post['ID'],
        'HEADING' => $post['UF_HEADING'],
        'BODY' => $post['UF_BODY'],
        'DATE' => $post['UF_DATE']->toString(),
        'AUTHOR' => $post['AUTHOR_NAME'] . ' ' . $post['AUTHOR_LASTNAME'],
        'CATEGORY' => $post['CATEGORY_TITLE']
    ];
    $row = ['id' => $key, 'columns' => $cols];
    $rows[] = $row;
}

$select = ['ID', 'UF_TITLE'];
$categories = $categoriesClass::getList(['select' => $select])->fetchAll();
$categories = array_column($categories, 'UF_TITLE', 'ID');
$filterParams = [
    'FILTER_ID' => $filterId,
    'GRID_ID' => $gridId,
    'FILTER' => [
        ['id' => 'CATEGORY', 'name' => 'Категория', 'type' => 'list', 'items' => $categories]
    ],
    'ENABLE_LABEL' => true
];
$APPLICATION->includeComponent('bitrix:main.ui.filter', '', $filterParams);

$gridParams = [
    'GRID_ID' => $gridId,
    'COLUMNS' => $columns,
    'ROWS' => $rows,
    'AJAX_MODE' => 'Y',
    'AJAX_OPTION_JUMP' => 'N',
    'AJAX_OPTION_HISTORY' => 'N'
];
$APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', $gridParams);

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
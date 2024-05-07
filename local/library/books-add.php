<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

use Bitrix\Main\Context;
use Library\BookTable;
use Library\StoreTable;
use Library\StoreBookTable;

$request = Context::getCurrent()->getRequest();
$storeId = $request->get('id');
$store = StoreTable::getByPrimary($storeId)->fetchObject();
$storeTitle = $store->getTitle();

$bookCollection = BookTable::getList(['select' => ['ID', 'TITLE', 'AUTHORS']])->fetchCollection();
$books = [];
foreach ($bookCollection as $book) {
    $item = [];
    $item['ID'] = $book->getId();
    $item['TITLE'] = $book->getTitle();
    $item['AUTHORS'] = [];
    $authors = $book->getAuthors()->getAll();
    foreach ($authors as $elem) {
        $item['AUTHORS'][] = $elem->getName();
    }
    $item['AUTHORS'] = implode(', ', $item['AUTHORS']);
    $books[] = $item;
}

if ($request->isPost()) {
    $params = $request->getPostList()->getValues();
    $count = [];
    foreach ($params as $key => $value) {
        $bookId = (int) substr($key, 8);
        $count[$bookId] = (int) $value;
    }

    foreach ($count as $key => $value) {
        if ($value == 0) continue;
        $book = BookTable::getByPrimary($key)->fetchObject();
        StoreBookTable::createObject()->setBook($book)->setStore($store)->setQuantity($value)->save();
    }

    LocalRedirect('/local/library/index.php');
}
?>

    <h1>Добавить книги в магазин - <?= $storeTitle ?></h1>
    <p style="margin-bottom:20px;"><a href="/local/library/index.php">Вернуться назад &rarr;</a></p>
    <hr>
    <form action="" method="post">
        <div style="display:grid;grid-template-columns:auto 1fr;grid-gap:20px;align-items:center;margin:20px;">
            <?php foreach ($books as $item) { ?>
                <div>
                    <strong><?= $item['TITLE'] ?></strong><br>
                    <?= $item['AUTHORS'] ?>
                </div>
                <div>
                    <div class="ui-ctl ui-ctl-textbox">
                        <input type="number" class="ui-ctl-element" name="quantity<?=$item['ID']?>">
                    </div>
                </div>
            <?php } ?>
        </div>

        <div style="margin-top:20px;grid-column:2/3;">
            <button type="submit" class="ui-btn ui-btn-primary ui-btn-icon-add">Сохранить</button>
        </div>
    </form>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';

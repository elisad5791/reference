<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

use Library\AuthorTable;
use Library\BookTable;
use Library\PublisherTable;
use Library\StoreTable;

$select = ['ID', 'TITLE', 'ISBN', 'PUBLISHER', 'PUBLISHER.CITY', 'AUTHORS', 'STORE_ITEMS', 'STORE_ITEMS.STORE'];
$bookCollection = BookTable::getList(['select' => $select])->fetchCollection();
$books = [];
foreach ($bookCollection as $book) {
    $item = [];
    $item['ID'] = $book->getId();
    $item['TITLE'] = $book->getTitle();
    $item['ISBN'] = $book->getIsbn();
    $item['PUB_TITLE'] = $book->getPublisher()->getTitle();
    $item['PUB_CITY'] = $book->getPublisher()->getCity()->getTitle();
    $arr = $book->getAuthors()->getAll();
    $item['AUTHORS'] = [];
    foreach ($arr as $elem) {
        $item['AUTHORS'][] = $elem->getName();
    }
    $sItems = $book->getStoreItems();
    $item['STORES'] = [];
    foreach($sItems as $sItem) {
        $store = [];
        $store['TITLE'] = $sItem->getStore()->getTitle();
        $store['QUANTITY'] = $sItem->getQuantity();
        $item['STORES'][] = $store;
    }
    $books[] = $item;
}

$select = ['ID', 'NAME', 'BOOKS'];
$authorCollection = AuthorTable::getList(['select' => $select])->fetchCollection();
$authors = [];
foreach ($authorCollection as $author) {
    $item = [];
    $item['ID'] = $author->getId();
    $item['NAME'] = $author->getName();
    $arr = $author->getBooks()->getAll();
    foreach ($arr as $elem) {
        $item['BOOKS'][] = $elem->getTitle();
    }
    $authors[] = $item;
}

$pubCollection = PublisherTable::getList(['select' => ['ID', 'TITLE', 'CITY', 'BOOKS']])->fetchCollection();
$publishers = [];
foreach ($pubCollection as $publisher) {
    $item = [];
    $item['ID'] = $publisher->getId();
    $item['TITLE'] = $publisher->getTitle();
    $item['CITY_NAME'] = $publisher->getCity()->getTitle();
    $item['BOOKS'] = [];
    $arr = $publisher->getBooks()->getAll();
    foreach ($arr as $elem) {
        $item['BOOKS'][] = $elem->getTitle();
    }
    $publishers[] = $item;
}

$select = ['ID', 'TITLE', 'ADDRESS', 'BOOK_ITEMS', 'BOOK_ITEMS.BOOK', 'BOOK_ITEMS.BOOK.AUTHORS'];
$storeCollection = StoreTable::getList(['select' => $select])->fetchCollection();
$stores = [];
foreach ($storeCollection as $store) {
    $item = [];
    $item['ID'] = $store->getId();
    $item['TITLE'] = $store->getTitle();
    $item['ADDRESS'] = $store->getAddress();
    $bItems = $store->getBookItems();
    $item['BOOKS'] = [];
    foreach($bItems as $bItem) {
        $book = [];
        $book['TITLE'] = $bItem->getBook()->getTitle();
        $bAuthors = $bItem->getBook()->getAuthors();
        $book['AUTHORS'] = [];
        foreach ($bAuthors as $bAuthor) {
            $book['AUTHORS'][] = $bAuthor->getName();
        }
        $book['AUTHORS'] = implode(', ', $book['AUTHORS']);
        $book['QUANTITY'] = $bItem->getQuantity();
        $item['BOOKS'][] = $book;
    }
    $stores[] = $item;
}
?>

<h1 style="margin-bottom:20px;">Список книг</h1>
<p><a href="/local/library/add.php">Добавить книги</a></p>
<table style="border:1px solid gray;border-collapse:collapse">
    <tr>
        <th style="border:1px solid gray;padding:5px 10px">№</th>
        <th style="border:1px solid gray;padding:5px 10px">Название</th>
        <th style="border:1px solid gray;padding:5px 10px">ISBN</th>
        <th style="border:1px solid gray;padding:5px 10px">Издательство</th>
        <th style="border:1px solid gray;padding:5px 10px">Город издания</th>
        <th style="border:1px solid gray;padding:5px 10px">Автор</th>
        <th style="border:1px solid gray;padding:5px 10px">Магазины</th>
    </tr>
    <?php foreach ($books as $book) { ?>
        <tr>
            <td style="border:1px solid gray;padding:5px 10px"><?= $book['ID'] ?></td>
            <td style="border:1px solid gray;padding:5px 10px"><?= $book['TITLE'] ?></td>
            <td style="border:1px solid gray;padding:5px 10px"><?= $book['ISBN'] ?></td>
            <td style="border:1px solid gray;padding:5px 10px"><?= $book['PUB_TITLE'] ?></td>
            <td style="border:1px solid gray;padding:5px 10px"><?= $book['PUB_CITY'] ?></td>
            <td style="border:1px solid gray;padding:5px 10px">
                <?php foreach ($book['AUTHORS'] as $name) { ?>
                    <p><?= $name ?></p>
                <?php } ?>
            </td>
            <td style="border:1px solid gray;padding:5px 10px">
                <?php foreach ($book['STORES'] as $store) { ?>
                    <p><?= $store['TITLE'] ?> - <?= $store['QUANTITY'] ?></p>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>

<h2 style="margin-bottom:0;">Список издательств</h2>
<div style="margin-bottom:10px;"><a href="/local/library/publisher-add.php">Добавить издательства</a></div>
<table style="border:1px solid gray;border-collapse:collapse">
    <tr>
        <th style="border:1px solid gray;padding:5px 10px">№</th>
        <th style="border:1px solid gray;padding:5px 10px">Название</th>
        <th style="border:1px solid gray;padding:5px 10px">Город</th>
        <th style="border:1px solid gray;padding:5px 10px">Книги</th>
    </tr>
    <?php foreach ($publishers as $publisher) { ?>
        <tr>
            <td style="border:1px solid gray;padding:5px 10px"><?= $publisher['ID'] ?></td>
            <td style="border:1px solid gray;padding:5px 10px"><?= $publisher['TITLE'] ?></td>
            <td style="border:1px solid gray;padding:5px 10px"><?= $publisher['CITY_NAME'] ?></td>
            <td style="border:1px solid gray;padding:5px 10px">
                <ul>
                    <?php foreach ($publisher['BOOKS'] as $pubbook) { ?>
                        <li><?= $pubbook ?></li>
                    <?php } ?>
                </ul>
            </td>

        </tr>
    <?php } ?>
</table>

<h2 style="margin-bottom:0;">Список авторов</h2>
<div style="margin-bottom:10px;"><a href="/local/library/author-add.php">Добавить автора</a></div>
<table style="border:1px solid gray;border-collapse:collapse">
    <tr>
        <th style="border:1px solid gray;padding:5px 10px">№</th>
        <th style="border:1px solid gray;padding:5px 10px">Имя</th>
        <th style="border:1px solid gray;padding:5px 10px">Книги</th>
    </tr>
    <?php foreach ($authors as $author) { ?>
        <tr>
            <td style="border:1px solid gray;padding:5px 10px"><?= $author['ID'] ?></td>
            <td style="border:1px solid gray;padding:5px 10px"><?= $author['NAME'] ?></td>
            <td style="border:1px solid gray;padding:5px 10px">
                <ul>
                    <?php foreach ($author['BOOKS'] as $authbook) { ?>
                        <li><?= $authbook ?></li>
                    <?php } ?>
                </ul>
            </td>

        </tr>
    <?php } ?>
</table>

<h2 style="margin-bottom:0;">Список магазинов</h2>
<div style="margin-bottom:10px;"><a href="/local/library/store-add.php">Добавить магазин</a></div>
<table style="border:1px solid gray;border-collapse:collapse">
    <tr>
        <th style="border:1px solid gray;padding:5px 10px">№</th>
        <th style="border:1px solid gray;padding:5px 10px">Название</th>
        <th style="border:1px solid gray;padding:5px 10px">Адрес</th>
        <th style="border:1px solid gray;padding:5px 10px">Книги</th>
    </tr>
    <?php foreach ($stores as $store) { ?>
        <tr>
            <td style="border:1px solid gray;padding:5px 10px"><?= $store['ID'] ?></td>
            <td style="border:1px solid gray;padding:5px 10px"><?= $store['TITLE'] ?></td>
            <td style="border:1px solid gray;padding:5px 10px"><?= $store['ADDRESS'] ?></td>
            <td style="border:1px solid gray;padding:5px 10px">
                <ul>
                <?php foreach ($store['BOOKS'] as $book) { ?>
                    <li><?= $book['TITLE'] ?> - <?= $book['AUTHORS'] ?> (<?= $book['QUANTITY'] ?>)</li>
                <?php } ?>
                </ul>
                <a href="/local/library/books-add.php?id=<?= $store['ID'] ?>">Добавить книги</a>
            </td>
        </tr>
    <?php } ?>
</table>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';

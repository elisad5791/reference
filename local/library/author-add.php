<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

use Bitrix\Main\Context;
use Library\Author;
use Library\BookTable;

$books = BookTable::getList(['select' => ['ID', 'TITLE']])->fetchAll();
$request = Context::getCurrent()->getRequest();

if ($request->isPost()) {
    $name = $request->get('name');
    $bookIds = $request->get('books');

    $author = new Author;
    $author->setName($name);
    $bookCol = BookTable::getList(['select' => ['*'], 'filter' => ['ID' => $bookIds]])->fetchCollection();
    foreach ($bookCol as $obj) {
        $author->addToBooks($obj);
    }

    $result = $author->save();
    if ($result->isSuccess()) {
        LocalRedirect('/local/library/index.php');
    } else {
        deb($result->getErrorMessages(), 'errors');
    }
}
?>

    <h1>Добавить автора</h1>
    <p style="margin-bottom:20px;"><a href="/local/library/index.php">Вернуться назад &rarr;</a></p>
    <hr>
    <form action="" method="post">
        <div style="display:grid;grid-template-columns:auto 1fr;grid-gap:10px;margin:20px;">
            <label>Имя</label>
            <div class="ui-ctl ui-ctl-textbox">
                <input type="text" class="ui-ctl-element" name="name">
            </div>
            <label>Книги</label>
            <div class="ui-ctl ui-ctl-multiple-select">
                <select class="ui-ctl-element" multiple name="books[]">
                    <?php foreach ($books as $item) { ?>
                        <option value="<?= $item['ID'] ?>"><?= $item['TITLE'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div style="margin-top:20px;grid-column:2/3;">
            <button type="submit" class="ui-btn ui-btn-primary ui-btn-icon-add">Сохранить</button>
        </div>
    </form>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';

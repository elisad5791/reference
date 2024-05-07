<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

use Bitrix\Main\Context;
use Library\Books;
use Library\Book;

$request = Context::getCurrent()->getRequest();
if ($request->isPost()) {
    $title = $request->get('title');
    $isbn = $request->get('isbn');

    $books = new Books;
    for ($i = 0; $i < 5; $i++) {
        if (!empty($title[$i]) && !empty($isbn[$i])) {
            $book = new Book;
            $book->setTitle($title[$i]);
            $book->setIsbn($isbn[$i]);
            $books->add($book);
        }
    }
    $result = $books->save(true);
    if ($result->isSuccess()) {
        LocalRedirect('/local/library/index.php');
    } else {
        deb($result->getErrorMessages(), 'errors');
    }
}
?>

    <h1>Добавить книги</h1>
    <p style="margin-bottom:20px;"><a href="/local/library/index.php">Вернуться назад &rarr;</a></p>
    <hr>
    <form action="" method="post">
        <?php for($i = 0; $i < 5; $i++) { ?>
            <div style="display:grid;grid-template-columns:auto 1fr;grid-gap:10px;margin:20px;">
                <label>Название</label>
                <div class="ui-ctl ui-ctl-textbox">
                    <input type="text" class="ui-ctl-element" name="title[]">
                </div>
                <label>ISBN</label>
                <div class="ui-ctl ui-ctl-textbox">
                    <input type="text" class="ui-ctl-element" name="isbn[]">
                </div>
            </div>
            <hr>
        <?php } ?>

        <div style="margin-top:20px;grid-column:2/3;">
            <button type="submit" class="ui-btn ui-btn-primary ui-btn-icon-add">Сохранить</button>
        </div>
    </form>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';

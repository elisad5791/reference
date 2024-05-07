<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

use Bitrix\Main\Context;
use Library\Store;

$request = Context::getCurrent()->getRequest();
if ($request->isPost()) {
    $title = $request->get('title');
    $address = $request->get('address');

    $store = new Store;
    $store->setTitle($title);
    $store->setAddress($address);

    $result = $store->save();
    if ($result->isSuccess()) {
        LocalRedirect('/local/library/index.php');
    } else {
        deb($result->getErrorMessages(), 'errors');
    }
}
?>

    <h1>Добавить магазин</h1>
    <p style="margin-bottom:20px;"><a href="/local/library/index.php">Вернуться назад &rarr;</a></p>
    <hr>
    <form action="" method="post">
        <div style="display:grid;grid-template-columns:auto 1fr;grid-gap:10px;margin:20px;">
            <label>Название</label>
            <div class="ui-ctl ui-ctl-textbox">
                <input type="text" class="ui-ctl-element" name="title">
            </div>
            <label>Адрес</label>
            <div class="ui-ctl ui-ctl-textbox">
                <input type="text" class="ui-ctl-element" name="address">
            </div>
        </div>
        <div style="margin-top:20px;grid-column:2/3;">
            <button type="submit" class="ui-btn ui-btn-primary ui-btn-icon-add">Сохранить</button>
        </div>
    </form>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';

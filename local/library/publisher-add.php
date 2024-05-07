<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

use Bitrix\Main\Context;
use Library\Publishers;
use Library\Publisher;
use Library\CityTable;

$cities = CityTable::getList(['select' => ['ID', 'TITLE']])->fetchAll();
$request = Context::getCurrent()->getRequest();

if ($request->isPost()) {
    $title = $request->get('title');
    $city = $request->get('city');

    $publishers = new Publishers;
    for ($i = 0; $i < 3; $i++) {
        if (!empty($title[$i]) && !empty($city[$i])) {
            $publisher = new Publisher;
            $publisher->setTitle($title[$i]);
            $publisher->setCityId($city[$i]);
            $publishers->add($publisher);
        }
    }
    $result = $publishers->save(true);
    if ($result->isSuccess()) {
        LocalRedirect('/local/library/index.php');
    } else {
        deb($result->getErrorMessages(), 'errors');
    }
}
?>

    <h1>Добавить издательства</h1>
    <p style="margin-bottom:20px;"><a href="/local/library/index.php">Вернуться назад &rarr;</a></p>
    <hr>
    <form action="" method="post">
        <?php for($i = 0; $i < 3; $i++) { ?>
            <div style="display:grid;grid-template-columns:auto 1fr;grid-gap:10px;margin:20px;">
                <label>Название</label>
                <div class="ui-ctl ui-ctl-textbox">
                    <input type="text" class="ui-ctl-element" name="title[]">
                </div>
                <label>Город</label>
                <div class="ui-ctl ui-ctl-after-icon ui-ctl-dropdown">
                    <div class="ui-ctl-after ui-ctl-icon-angle"></div>
                    <select class="ui-ctl-element" name="city[]">
                        <?php foreach ($cities as $item) { ?>
                            <option value="<?= $item['ID'] ?>"><?= $item['TITLE'] ?></option>
                        <?php } ?>
                    </select>
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

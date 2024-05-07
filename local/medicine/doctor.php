<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

use Bitrix\Iblock\Elements\ElementDoctorsTable;
use Bitrix\Main\Context;
use Bitrix\Main\Page\Asset;

Asset::getInstance()->addCss('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');

$request = Context::getCurrent()->getRequest();
$elementId = $request->get('id');

$select = ['ID', 'NAME', 'SCHEDULE', 'PHOTO.FILE', 'PROCEDURES.ELEMENT', 'PROCEDURES.ELEMENT.PRICE'];
$element = ElementDoctorsTable::getByPrimary($elementId, ['select' => $select])->fetchObject();
$name = $element->getName();
$schedule = $element->get('SCHEDULE')->getValue();

$photo = $element->getPhoto();
$subdir = $photo->getFile()->getSubdir();
$filename = $photo->getFile()->getFileName();
$filepath = '/upload/' . $subdir . '/' . $filename;

$res = $element->getProcedures()->getAll();
$procedures = [];
foreach ($res as $procedure) {
    $elem = $procedure->getElement();
    $id = $elem->getId();
    $title = $elem->getName();
    $price = $elem->getPrice()->getValue();
    $procedures[] = ['id' => $id, 'name' => $title, 'price' => $price];
}
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-4 col-xl-3 col-xxl-2">
            <img class="mw-100" src="<?= $filepath ?>" alt="">
        </div>
        <div class="col-8 col-xl-6 col-xxl-4">
            <h1><?= $name ?> </h1>
            <h5 class="mt-3">График работы</h5>
            <p class="mt-3"><?= $schedule ?></p>
            <h5>Проводимые процедуры</h5>
            <ul class="mt-3">
                <?php foreach ($procedures as $item) { ?>
                    <li>
                        <a href="/local/medicine/procedure.php?id=<?=$item['id']?>">
                            <?= $item['name'] ?>
                        </a>
                        - <?= $item['price'] ?> руб.
                    </li>
                <?php } ?>
            </ul>
            <p class="mt-5">
                <a href="/local/medicine/index.php">Вернуться на главную &rarr;</a>
            </p>
        </div>
    </div>
</div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';


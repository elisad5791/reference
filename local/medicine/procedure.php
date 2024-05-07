<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

use Bitrix\Iblock\Elements\ElementProceduresTable;
use Bitrix\Iblock\Elements\ElementDoctorsTable;
use Bitrix\Main\Context;
use Bitrix\Main\Page\Asset;

Asset::getInstance()->addCss('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');

$request = Context::getCurrent()->getRequest();
$elementId = $request->get('id');

$select = ['ID', 'NAME', 'PRICE', 'PICTURE.FILE'];
$element = ElementProceduresTable::getByPrimary($elementId, ['select' => $select])->fetchObject();
$name = $element->getName();
$price = $element->get('PRICE')->getValue();

$picture = $element->getPicture();
$subdir = $picture->getFile()->getSubdir();
$filename = $picture->getFile()->getFileName();
$filepath = '/upload/' . $subdir . '/' . $filename;

$select = ['ID', 'NAME', 'SCHEDULE'];
$filter = ['PROCEDURES.VALUE' => $elementId];
$res = ElementDoctorsTable::getList(['select' => $select, 'filter' => $filter])->fetchCollection();
$doctors = [];
foreach ($res as $item) {
    $id = $item->getId();
    $fio = $item->getName();
    $schedule = $item->getSchedule()->getValue();
    $doctors[] = ['id' => $id, 'name' => $fio, 'schedule' => $schedule];
}
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-4 col-xl-3 col-xxl-2">
            <img class="mw-100" src="<?= $filepath ?>" alt="">
        </div>
        <div class="col-8 col-xl-6 col-xxl-4">
            <h1><?= $name ?> </h1>
            <h5 class="mt-3">Стоимость</h5>
            <p class="mt-3"><?= $price ?> руб.</p>
            <h5>Специалисты</h5>
            <ul class="mt-3">
                <?php foreach ($doctors as $item) { ?>
                    <li>
                        <a href="/local/medicine/doctor.php?id=<?=$item['id']?>">
                            <?= $item['name'] ?>
                        </a>
                        - <?= $item['schedule'] ?>
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
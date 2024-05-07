<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

use Bitrix\Iblock\Elements\ElementDoctorsTable;
use Bitrix\Iblock\Elements\ElementProceduresTable;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Context;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\UI\Extension;

$iblockCode = 'doctors';
$codeSchedule = 'SCHEDULE';
$codePhoto = 'PHOTO';
$codeProcedures = 'PROCEDURES';

$res = IblockTable::getList(['filter' => ['CODE' => $iblockCode]])->fetch();
$iblockId = $res['ID'];

$select =  ['ID', 'CODE'];
$filter = ['IBLOCK_ID' => $iblockId];
$res = PropertyTable::getList(['select' => $select, 'filter' => $filter])->fetchAll();
foreach ($res as $prop) {
    if ($prop['CODE'] == $codeSchedule) $propSchedule = $prop['ID'];
    if ($prop['CODE'] == $codePhoto) $propPhoto = $prop['ID'];
    if ($prop['CODE'] == $codeProcedures) $propProcedures = $prop['ID'];
}

$request = Context::getCurrent()->getRequest();

if ($request->isPost()) {
    $fio = $request->get('fio');
    $schedule = $request->get('schedule');
    $procedures = $request->get('procedures');
    $photo = $request->getFile('photo');

    $props = [];
    $props[$propSchedule] = $schedule;
    $props[$propPhoto] = CFile::MakeFileArray($photo['tmp_name']);
    $props[$propProcedures] = $procedures;
    $fields = [
        'NAME' => $fio,
        'IBLOCK_ID' => $iblockId,
        'PROPERTY_VALUES' => $props
    ];
    $el = new CIBlockElement;
    if ($el->Add($fields)) {
        LocalRedirect('/local/medicine/index.php');
    } else {
        echo $el->LAST_ERROR;
    }
}

Asset::getInstance()->addCss('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
Extension::load('ui.layout-form');
Extension::load('ui.buttons.icons');

$select = ['ID', 'NAME'];
$procedures = ElementProceduresTable::getList(['select' => $select, 'order' => ['ID']])->fetchAll();
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-8 col-xl-6 col-xxl-4">
            <h1 class="text-center mb-4">Добавить врача</h1>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="ui-form">

                    <div class="ui-form-row">
                        <div class="ui-form-label">
                            <div class="ui-ctl-label-text">
                                ФИО<span class="text-danger">*</span>
                            </div>
                        </div>
                        <div class="ui-form-content">
                            <div class="ui-ctl ui-ctl-textbox ui-ctl-w100">
                                <input type="text" class="ui-ctl-element" name="fio" required>
                            </div>
                        </div>
                    </div>

                    <div class="ui-form-row">
                        <div class="ui-form-label">
                            <div class="ui-ctl-label-text">
                                График работы<span class="text-danger">*</span>
                            </div>
                        </div>
                        <div class="ui-form-content">
                            <div class="ui-ctl ui-ctl-textbox ui-ctl-w100">
                                <input type="text" class="ui-ctl-element" name="schedule" required>
                            </div>
                        </div>
                    </div>

                    <div class="ui-form-row">
                        <div class="ui-form-label">
                            <div class="ui-ctl-label-text">
                                Процедуры<span class="text-danger">*</span>
                            </div>
                        </div>
                        <div class="ui-form-content">
                            <div class="ui-ctl ui-ctl-multiple-select ui-ctl-w100">
                                <select class="ui-ctl-element" name="procedures[]" multiple required>
                                    <?php foreach ($procedures as $item) { ?>
                                        <option value="<?= $item['ID'] ?>"><?= $item['NAME'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="ui-form-row">
                        <label class="ui-ctl ui-ctl-file-btn">
                            <input type="file" class="ui-ctl-element" name="photo" required>
                            <div class="ui-ctl-label-text">
                                Добавить фотографию<span class="text-danger">*</span>
                            </div>
                        </label>
                    </div>

                    <div class="ui-form-row">
                        <button class="ui-btn ui-btn-primary ui-btn-icon-done">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

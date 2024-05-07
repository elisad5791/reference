<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

use Bitrix\Iblock\Elements\ElementDoctorsTable;
use Bitrix\Iblock\Elements\ElementProceduresTable;
use Bitrix\Main\UI\Extension;
use Bitrix\Main\Page\Asset;

$doctors = ElementDoctorsTable::getList(['select' => ['ID', 'NAME']])->fetchAll();
$procedures = ElementProceduresTable::getList(['select' => ['ID', 'NAME']])->fetchAll();

Extension::load('ui.buttons.icons');
Asset::getInstance()->addCss('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
?>

<div class="container-fluid">
    <h1 class="text-center mb-4">Медицинские услуги</h1>
    <div class="row justify-content-center">
        <div class="col-12 col-sm-5 col-xl-4">
            <div class="card">
                <div class="card-header">
                    Врачи
                </div>
                <div class="card-body">
                    <?php foreach ($doctors as $doctor) { ?>
                        <p><a href="/local/medicine/doctor.php?id=<?=$doctor['ID']?>"><?= $doctor['NAME'] ?></a></p>
                    <?php } ?>
                    <p>
                        <a href="/local/medicine/add-doctor.php" class="ui-btn ui-btn-primary ui-btn-icon-add">
                            Добавить врача
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-5 col-xl-4">
            <div class="card">
                <div class="card-header">
                    Процедуры
                </div>
                <div class="card-body">
                    <?php foreach ($procedures as $procedure) { ?>
                        <p>
                            <a href="/local/medicine/procedure.php?id=<?=$procedure['ID']?>">
                                <?= $procedure['NAME'] ?>
                            </a>
                        </p>
                    <?php } ?>
                    <p>
                        <a href="/local/medicine/add-procedure.php" class="ui-btn ui-btn-primary ui-btn-icon-add">
                            Добавить процедуру
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Elisad\Clean\Master;

Loc::loadMessages(__FILE__);

$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialcharsbx($request['mid'] != '' ? $request['mid'] : $request['id']);
Loader::includeModule($module_id);

$tip1 = 'Если флажок не активен, бекап автоматически создастся но файлы из /upload/iblock/ не будут удалены. 
            Если флажок активен, можно выбрать ниже параметр Сохранять в бекап найденые файлы';
$tip2 = 'Если флажок активен, бекап создастся, файлы будут сохранены в /upload/iblock_Backup';
$options = [
    'Удалять найденые файлы?',
    ['deletefiles', $tip1, 'N', ['checkbox']],
    'Сохранять в бекап найденые файлы?',
    ['savebackup', $tip2, 'N', ['checkbox']]
];
$aTabs = [
    [
        'DIV' => 'elisad1',
        'TAB' => 'Удаление ненужных файлов из папки /upload/iblock',
        'TITLE' => 'Удаление ненужных файлов из папки /upload/iblock',
        'OPTIONS' => $options
    ]
];

if ($request->isPost() && check_bitrix_sessid()) {
    if ($request['delete']) {
        new Master();
    }
}

$tabControl = new CAdminTabControl('tabControl', $aTabs);
$tabControl->Begin();
?>

    <form action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= $module_id ?>&lang=<?= LANG ?>" method="post">
        <?php
        foreach ($aTabs as $aTab) {
            if ($aTab['OPTIONS']) {
                $tabControl->BeginNextTab();
                __AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
            }
        }
        $tabControl->Buttons();
        echo bitrix_sessid_post();
        ?>
        <input class="adm-btn-save" type="submit" name="Update" value="Сохранить настройки"/>
        <input type="submit" name="delete" value="Очистить папку"/>
    </form>

<?php
$tabControl->End();
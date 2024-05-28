<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialcharsbx($request['mid'] != '' ? $request['mid'] : $request['id']);

$RIGHTS = $APPLICATION->GetGroupRight($module_id);
if ($RIGHTS < 'S') {
    $APPLICATION->AuthForm(Loc::getMessage('ACCESS_DENIED'));
}

Loader::includeModule($module_id);

$selectChoices = ['460' => '460Х306', '360' => '360Х242'];
$options = [
    'Название секции checkbox',
    ['elisad_checkbox', 'Поясняющий текст элемента checkbox', 'Y', ['checkbox']],
    'Название секции text',
    ['elisad_text', 'Поясняющий текст элемента text', 'Жми!', ['text', 10, 50]],
    'Название секции selectbox',
    ['elisad_selectbox', 'Поясняющий текст элемента selectbox', '460', ['selectbox', $selectChoices]]
];

$aTabs = [
    [
        'DIV' => 'edit1',
        'TAB' => 'Название вкладки в табах',
        'TITLE' => 'Главное название в админке',
        'OPTIONS' => $options
    ]
];

if ($request->isPost() && check_bitrix_sessid()) {
    foreach ($aTabs as $aTab) {
        foreach ($aTab['OPTIONS'] as $arOption) {
            if (!is_array($arOption)) {
                continue;
            }

            if ($request['Update']) {
                $optionValue = $request->getPost($arOption[0]);
                if ($arOption[0] == 'elisad_checkbox') {
                    $optionValue = $optionValue == '' ? 'N' : 'Y';
                }
                Option::set($module_id, $arOption[0], $optionValue);
            }

            if ($request['default']) {
                Option::set($module_id, $arOption[0], $arOption[2]);
            }
        }
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
        <input class="adm-btn-save" type="submit" name="Update" value="Применить"/>
        <input type="submit" name="default" value="По умолчанию"/>
    </form>

<?php
$tabControl->End();
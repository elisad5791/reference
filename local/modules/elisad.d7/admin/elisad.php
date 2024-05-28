<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';

use Bitrix\Main\Loader;
use Elisad\d7\DataTable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type;

Loc::loadMessages(__FILE__);

$RIGHTS = $APPLICATION->GetGroupRight('elisad.d7');
if ($RIGHTS == 'D') {
    $APPLICATION->AuthForm(Loc::getMessage('ACCESS_DENIED'));
}

$APPLICATION->SetTitle('Настройка');

$aTabs = [['TAB' => 'Параметры', 'TITLE' => 'Параметры вывода']];
$tabControl = new CAdminTabControl('tabControl', $aTabs);
Loader::includeModule('elisad.d7');

if ($REQUEST_METHOD == 'POST' && $save != '' &&  $RIGHTS == 'W' && check_bitrix_sessid()) {
    $bookTable = new DataTable;
    $arFields = [
        'ACTIVE' => ($ACTIVE == '') ? 'N' : 'Y',
        'SITE' => htmlspecialchars($SITE),
        'LINK' => htmlspecialchars($LINK),
        'LINK_PICTURE' => htmlspecialchars($LINK_PICTURE),
        'ALT_PICTURE' => htmlspecialchars($ALT_PICTURE),
        'EXCEPTIONS' => $EXCEPTIONS == '' ? '' : trim(htmlspecialchars($EXCEPTIONS)),
        'DATE' => new Type\DateTime(date("d.m.Y H:i:s")),
        'TARGET' => htmlspecialchars($TARGET)
    ];
    $res = $bookTable->Update(1, $arFields);
    if ($res->isSuccess()) {
        if ($save != '') {
            LocalRedirect('/bitrix/admin/elisad.php?mess=ok&lang=' . LANG);
        }
    }
    if (!$res->isSuccess()) {
        if ($e = $APPLICATION->GetException())
            $message = new CAdminMessage('Ошибка сохранения: ', $e);
        else {
            $mess = print_r($res->getErrorMessages(), true);
            $message = new CAdminMessage('Ошибка сохранения: ' . $mess);
        }
    }
}

$result = DataTable::GetByID(1);
if ($result->getSelectedRowsCount()) {
    $bookTable = $result->fetch();
    $STR_ACTIVE = $bookTable['ACTIVE'];
    $STR_SITE = $bookTable['SITE'];
    $STR_LINK = $bookTable['LINK'];
    $STR_LINK_PICTURE = $bookTable['LINK_PICTURE'];
    $STR_ALT_PICTURE = $bookTable['ALT_PICTURE'];
    $STR_EXCEPTIONS = $bookTable['EXCEPTIONS'];
    $STR_TARGET = $bookTable['TARGET'];
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';

if ($_REQUEST['mess'] == 'ok') {
    CAdminMessage::ShowMessage(['MESSAGE' => 'Сохранено успешно', 'TYPE' => 'OK']);
}
if ($message) {
    echo $message->Show();
}
if ($bookTable->LAST_ERROR != '') {
    CAdminMessage::ShowMessage($bookTable->LAST_ERROR);
}
?>

<form method="POST" action="<?= $APPLICATION->GetCurPage() ?>" ENCTYPE="multipart/form-data" name="post_form">
    <?php
    echo bitrix_sessid_post();
    $tabControl->Begin();
    $tabControl->BeginNextTab();
    ?>

    <tr>
        <td style="width:40%">Активность</td>
        <td style="width:60%">
            <input type="checkbox" name="ACTIVE" value="Y" <?= $STR_ACTIVE == "Y" ? 'checked' : '' ?>>
        </td>
    </tr>
    <tr>
        <td>Сайты</td>
        <td>
            <input type="text" name="SITE" value="<?= $STR_SITE ?>">
        </td>
    </tr>
    <tr>
        <td style="width:40%">Ссылка для перехода</td>
        <td style="width:60%">
            <input type="text" name="LINK" value="<?= $STR_LINK ?>">
        </td>
    </tr>
    <tr>
        <td style="width:40%">Ссылка на картинку</td>
        <td style="width:60%">
            <input type="text" name="LINK_PICTURE" value="<?= $STR_LINK_PICTURE ?>">
        </td>
    </tr>
    <tr>
        <td style="width:40%">Alt картинки</td>
        <td style="width:60%">
            <input type="text" name="ALT_PICTURE" value="<?= $STR_ALT_PICTURE ?>">
        </td>
    </tr>
    <tr>
        <td style="width:40%">Исключения</td>
        <td style="width:60%">
            <textarea cols="50" rows="15" name="EXCEPTIONS"><?= $STR_EXCEPTIONS ?></textarea>
        </td>
    </tr>
    <tr>
        <td style="width:40%">Значение TARGET (self/blank)</td>
        <td style="width:60%">
            <input type="text" name="TARGET" value="<?= $STR_TARGET ?>">
        </td>
    </tr>

    <?php
    $tabControl->Buttons();
    ?>

    <input class="adm-btn-save" type="submit" name="save" value="Сохранить настройки"/>

    <?php
    $tabControl->End();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';
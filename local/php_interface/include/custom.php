<?php
use Bitrix\Main\UI\Extension;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Page\Asset;

Extension::load('ui.buttons');
Extension::load('ui.buttons.icons');

CJSCore::RegisterExt('example', [
    'js' => '/local/js/example.js',
    'lang' => '/local/lang/' . LANGUAGE_ID . '/example.php',
    'css' => '/local/css/example.css',
    'rel' => ['popup']
]);
CJSCore::Init(['example']);

$asset = Asset::getInstance();
$page = $APPLICATION->GetCurPage();

/*--- модификация карточки канбана через js ---*/
if (str_contains($page, 'crm/lead/kanban')) {
    $asset->addString('<script>BX.ready(function () { BX.Elisad.modifyKanbanCard(); });</script>');
}

/*--- Добавление нового пункта в меню Диска через js ---*/
$asset->addString('<script>BX.ready(function () { BX.Elisad.registerDiskEvent(); });</script>');

/*--- Создание кнопки в профиле пользователя через js ---*/
$template = Option::get('intranet', 'path_user', '', SITE_ID);
$profileTemplates = ['profile' => ltrim($template, '/')];

$result = CComponentEngine::parseComponentPath('/', $profileTemplates, $arVars);
if ($result == 'profile') {
    $asset->addString('<script>BX.ready(function () { BX.Elisad.createProfileButton(); });</script>');
}

/*--- Добавление кнопки в список лидов через отложенные функции ---*/

ob_start();
?>
    <button class="ui-btn ui-btn-danger ui-btn-icon-phone-down">Кнопарь</button>
<?php
$html = ob_get_clean();
if (str_contains($page, 'crm/lead/list')) {
    $APPLICATION->AddViewContent('below_pagetitle', $html, 20000);
}
<?php

use Bitrix\Main\Page\Asset;
use CComponentEngine;

CJSCore::RegisterExt('customization', [
    'js' => '/local/js/customization.js'
]);
CJSCore::Init(['customization']);

$curPage = $APPLICATION->GetCurPage();
$asset = Asset::getInstance();

if (str_contains($curPage, '/tasks/task/view/')) {
    $asset->addString('<script>BX.ready(function () { BX.Customization.addTaskButton(); });</script>');
    $asset->addString('<script>BX.ready(function () { BX.Customization.addTaskMenu(); });</script>');
}

$templates = ['tasks' => 'company/personal/user/#USER_ID#/tasks/'];
$arVariables = [];
$page = CComponentEngine::ParseComponentPath('/', $templates, $arVariables);
echo $page;
if ($page) {
    $asset->addCss('/local/css/create-task.css');
}
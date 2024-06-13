<?php

use Bitrix\Main\Page\Asset;

CJSCore::RegisterExt('complete-task', [
    'js' => '/local/js/complete-task.js'
]);
CJSCore::Init(['complete-task']);

$asset = Asset::getInstance();
$asset->addString('<script>BX.ready(function () { BX.Working.registerCompleteAction(); });</script>');
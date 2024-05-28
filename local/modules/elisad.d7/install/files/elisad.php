<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$rsHandlers = GetModuleEvents('elisad.d7', 'OnSomeEvent');

while ($arHandler = $rsHandlers->Fetch()) {
    ExecuteModuleEventEx($arHandler, []);
}

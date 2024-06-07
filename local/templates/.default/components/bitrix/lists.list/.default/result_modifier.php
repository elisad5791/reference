<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->__IncludeCSSFile();
$this->__IncludeJSFile();

$this->__file = '/bitrix/components/bitrix/lists.list/templates/.default/template.php';
$this->__folder = '/bitrix/components/bitrix/lists.list/templates/.default';

$this->IncludeLangFile();
$this->__IncludeMutatorFile($arResult, $arParams);
$this->__IncludeCSSFile();
$this->__IncludeJSFile();

$includePath = __DIR__ . '/include/';
$fileList = array_filter(scandir($includePath), function($key) {
    return preg_match('/\.php/', $key);
});
foreach ($fileList as $file) {
    if (file_exists($includePath . $file)) {
        include_once $includePath . $file;
    }
}
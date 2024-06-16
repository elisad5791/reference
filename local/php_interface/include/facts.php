<?php
use Bitrix\Main\EventManager;

include_once __DIR__ . '/../classes/autoload.php';

CJSCore::RegisterExt('facts', [
    'js' => '/local/js/facts.js'
]);
CJSCore::Init(['facts']);

$manager = EventManager::getInstance();
$handler = ['Events', 'addMenuItem'];
$manager->addEventHandler('main', 'OnEpilog', $handler);
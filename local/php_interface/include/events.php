<?php
use Bitrix\Main\EventManager;

$manager = EventManager::getInstance();

$handler = ['MainGroupBinding', 'getUserTypeDescription'];
$manager->addEventHandler('main', 'OnUserTypeBuildList', $handler);

$handler = ['IblockGroupBinding', 'getUserTypeDescription'];
$manager->addEventHandler('iblock', 'OnIBlockPropertyBuildList', $handler);

$handler = ['CurrencyField', 'getUserTypeDescription'];
$manager->addEventHandler('main', 'OnUserTypeBuildList', $handler);

$handler = ['IBLink', 'GetUserTypeDescription'];
$manager->AddEventHandler('iblock', 'OnIBlockPropertyBuildList', $handler);

$handler = ['ReservationField', 'GetUserTypeDescription'];
$manager->AddEventHandler('iblock', 'OnIBlockPropertyBuildList', $handler);
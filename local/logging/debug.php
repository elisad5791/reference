<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Application;
use Bitrix\Iblock\ElementTable;

define('FILE_LOG', 'local/logs/logOtus.txt');

/*--- вывод логов и информации на экран ------------------------*/
$var = date('Y-m-d H:i:s');
$varName = 'дата';
Debug::writeToFile($var, $varName, FILE_LOG);
Debug::dumpToFile($var, $varName, FILE_LOG);
Debug::dump($var, $varName);

/*--- замер времени работы кода ---------------------------------*/
Debug::startTimeLabel('foo');
sleep(2);
Debug::endTimeLabel('foo');
$labels = Debug::getTimeLabels(); 
Debug::dump($labels, 'timelabels');

/*--- трекинг sql-запросов - один запрос -------------------------*/
$connection = Application::getConnection();
$connection->startTracker();
$select = ['ID', 'NAME'];
$filter = ['IBLOCK_ID' => 1];
$query = ElementTable::getList(['select' => $select, 'filter' => $filter]);
$connection->stopTracker();
$result = $query->getTrackerQuery()->getSql();
Debug::dump($result, 'sql-tracker');

/*--- трекинг sql-запросов - несколько запросов -------------------*/
$tracker = $connection->startTracker();
$select = ['ID', 'NAME'];
$filter = ['IBLOCK_ID' => 1];
$query1 = ElementTable::getList(['select' => $select, 'filter' => $filter]);
$select = ['ID', 'NAME'];
$filter = ['IBLOCK_ID' => 2];
$query2 = ElementTable::getList(['select' => $select, 'filter' => $filter]);
$connection->stopTracker();

foreach ($tracker->getQueries() as $query) {
    Debug::dump($query->getSql());
    Debug::dump($query->getTime());
}
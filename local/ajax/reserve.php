<?php

use Bitrix\Iblock\Elements\ElementDoctorsTable;
use Bitrix\Iblock\Elements\ElementReservationsTable;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$name = trim(htmlspecialchars(strip_tags($_POST['name'])));
$dt = trim(htmlspecialchars(strip_tags($_POST['dt'])));
$procedureId = (int)$_POST['procedure'];
$doctorId = (int)$_POST['doctor'];
if (empty($name) || empty($dt) || empty($procedure) || empty($doctorId)) {
    $result = ['error' => 'Заполните поля'];
    echo json_encode($result);
    die();
}

$select = ['PROC_' => 'PROCEDURES_RESERVATION'];
$filter = ['ID' => $doctorId];
$procedures = ElementDoctorsTable::getList(['select' => $select, 'filter' => $filter])->fetchAll();
$procedures = array_column($procedures, 'PROC_VALUE');
$procedureIds = array_map(fn($item) => (int)$item, $procedures);

$select = ['ID', 'IBLOCK_ID', 'RESERV_' => 'RESERVED_TIME', 'PROC_' => 'PROCEDURE'];
$filter = ['PROC_VALUE' => $procedureIds];
$reservations = ElementReservationsTable::getList(['select' => $select, 'filter' => $filter])->fetchAll();
$reservations = array_column($reservations, 'RESERV_VALUE');
$reservations = array_map(fn($item) => strtotime($item) + 7200, $reservations);

$newTime = strtotime($dt);
$enabled = true;
foreach ($reservations as $item) {
    $diff = abs($item - $newTime);
    if ($diff < 3600) {
        $enabled = false;
    }
}
if (!$enabled) {
    $result = ['error' => 'Выбранное время уже занято'];
    echo json_encode($result);
    die();
}

$el = new CIBlockElement;
$props = [67 => $procedureId, 68 => $dt];
$fields = [
    'IBLOCK_ID' => 18,
    'NAME' => $name,
    'PROPERTY_VALUES' => $props
];
$id = $el->Add($fields);

if ($id) {
    $result = ['href' => '/services/lists/18/view/0/'];
} else {
    $result = ['error' => $el->LAST_ERROR];
}

echo json_encode($result);
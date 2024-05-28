<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();

$column = 'A';
foreach ($arResult['EXCEL_COLUMN_NAME'] as $columnName) {
    $cell = $column . '1';
    $activeWorksheet->setCellValue($cell, $columnName);
    $column++;
}

foreach ($arResult['EXCEL_CELL_VALUE'] as $key => $row) {
    $column = 'A';
    $ind = $key + 2;
    foreach ($row as $value) {
        $cell = $column . $ind;
        $activeWorksheet->setCellValue($cell, $value);
        $column++;
    }
}

$writer = new Xlsx($spreadsheet);
$APPLICATION->RestartBuffer();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
$writer->save('php://output');
exit();
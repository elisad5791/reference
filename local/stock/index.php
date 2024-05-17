<?php

use Stock\ProductTable;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Iblock\Elements\ElementInvoicesTable;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

$query = ProductTable::query();

$join = Join::on('this.INVOICE', 'ref.NAME');
$field = new Reference('INV', ElementInvoicesTable::class, $join);
$query->registerRuntimeField($field);

$select = [
    'ID', 'TITLE', 'UNIT', 'QUANTITY', 'PRICE', 'RECEIPT_DATE', 'INVOICE',
    'PROVIDER_TITLE' => 'PROVIDER.NAME', 'PROVIDER_CITY' => 'PROVIDER.CITY.VALUE',
    'PROVIDER_TYPE' => 'PROVIDER.TYPE.VALUE', 'RESPONSIBLE_NAME' => 'RESPONSIBLE.NAME',
    'INVOICE_DATE' => 'INV.DATE.VALUE', 'INVOICE_SALESMAN' => 'INV.SALESMAN.VALUE',
    'INVOICE_SALESMAN_INN' => 'INV.SALESMAN_INN.VALUE',
    'INVOICE_BUYER' => 'INV.BUYER.VALUE', 'INVOICE_BUYER_INN' => 'INV.BUYER_INN.VALUE'
];
$query->setSelect($select);

$query->setOrder(['ID' => 'ASC']);
$query->setCacheTtl(3600);
$query->cacheJoins(true);
$products = $query->fetchAll();

$gridId = 'PRODUCTS_GRID';

$columns = [
    ['id' => 'ID', 'name' => '№', 'default' => true],
    ['id' => 'TITLE', 'name' => 'Название', 'default' => true],
    ['id' => 'UNIT', 'name' => 'Единица измерения', 'default' => true],
    ['id' => 'QUANTITY', 'name' => 'Количество', 'default' => true],
    ['id' => 'PRICE', 'name' => 'Цена', 'default' => true],
    ['id' => 'RECEIPT_DATE', 'name' => 'Дата поступления', 'default' => true],
    ['id' => 'PROVIDER', 'name' => 'Поставщик', 'default' => true],
    ['id' => 'PROVIDER_CITY', 'name' => 'Поставщик - Город', 'default' => true],
    ['id' => 'PROVIDER_TYPE', 'name' => 'Поставщик - тип организации', 'default' => true],
    ['id' => 'RESPONSIBLE', 'name' => 'Ответственный кладовщик', 'default' => true],
    ['id' => 'INVOICE', 'name' => 'Счет-фактура', 'default' => true],
    ['id' => 'INVOICE_DATE', 'name' => 'Счет-фактура - Дата', 'default' => true],
    ['id' => 'INVOICE_SALESMAN', 'name' => 'Счет-фактура - Продавец', 'default' => true],
    ['id' => 'INVOICE_SALESMAN_INN', 'name' => 'Счет-фактура - ИНН продавца', 'default' => true],
    ['id' => 'INVOICE_BUYER', 'name' => 'Счет-фактура - Покупатель', 'default' => true],
    ['id' => 'INVOICE_BUYER_INN', 'name' => 'Счет-фактура - ИНН покупателя', 'default' => true]
];

$rows = [];
foreach ($products as $key => $product) {
    $cols = [
        'ID' => $product['ID'],
        'TITLE' => $product['TITLE'],
        'UNIT' => $product['UNIT'],
        'QUANTITY' =>  $product['QUANTITY'],
        'PRICE' => $product['PRICE'],
        'RECEIPT_DATE' => $product['RECEIPT_DATE'],
        'PROVIDER' =>  $product['PROVIDER_TITLE'],
        'PROVIDER_CITY' => $product['PROVIDER_CITY'],
        'PROVIDER_TYPE' => $product['PROVIDER_TYPE'],
        'RESPONSIBLE' => $product['RESPONSIBLE_NAME'],
        'INVOICE' => $product['INVOICE'],
        'INVOICE_DATE' => $product['INVOICE_DATE'],
        'INVOICE_SALESMAN' => $product['INVOICE_SALESMAN'],
        'INVOICE_SALESMAN_INN' => $product['INVOICE_SALESMAN_INN'],
        'INVOICE_BUYER' => $product['INVOICE_BUYER'],
        'INVOICE_BUYER_INN' => $product['INVOICE_BUYER_INN']
    ];
    $row = ['id' => $key, 'columns' => $cols];
    $rows[] = $row;
}

$gridParams = [
    'GRID_ID' => $gridId,
    'COLUMNS' => $columns,
    'ROWS' => $rows,
    'AJAX_MODE' => 'Y',
    'AJAX_OPTION_JUMP' => 'N',
    'AJAX_OPTION_HISTORY' => 'N'
];
$APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', $gridParams);

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
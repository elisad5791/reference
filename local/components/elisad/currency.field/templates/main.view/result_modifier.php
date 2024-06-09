<?php
defined('B_PROLOG_INCLUDED') || die;

$component = $this->getComponent();
$value = (array)$arResult['value'] ?? [];

$arResult['formattedValue'] = [];
foreach ($value as $currencyId) {
    $arResult['formattedValue'][$currencyId] = $component->formatCurrency($currencyId);
}
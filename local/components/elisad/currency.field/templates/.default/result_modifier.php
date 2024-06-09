<?php
defined('B_PROLOG_INCLUDED') || die;

if ($component->isMultiple()) {
    $arResult['value'] = [$arResult['value']];
}

$arResult['formattedValue'] = [];
foreach ($arResult['value'] as $currencyId) {
    $arResult['formattedValue'][] = $component->formatCurrency($currencyId);
}
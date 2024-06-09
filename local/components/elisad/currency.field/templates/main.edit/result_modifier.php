<?php
defined('B_PROLOG_INCLUDED') || die;

$component = $this->getComponent();
$arResult['availableCurrencies'] = $component->getAvailableCurrencies();
<?php
defined('B_PROLOG_INCLUDED') || die;

print $component->getHtmlBuilder()->wrapSingleField(implode(',', $arResult['formattedValue']));
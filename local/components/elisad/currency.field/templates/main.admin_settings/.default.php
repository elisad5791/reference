<?php

use Bitrix\Main\UI\Extension;

defined('B_PROLOG_INCLUDED') || die;

$additionalParameters = $arResult['additionalParameters'];
Extension::load('ui.hint');
$hint = 'В каком формате показывать валюту - #FULL_NAME# - полное название, #SYMBOL# - символ валюты';
?>

<tr>
    <td>
        <div id="currency-format-setting">
            <span>Формат вывода</span>
            <span data-hint-html data-hint="<?= $hint ?>"></span>
        </div>
    </td>
    <td>
        <input
                type="text"
                name="<?= $additionalParameters['NAME']; ?>[FORMAT]"
                size="50"
                maxlength="255"
                value="<?= $arResult['values']['format']; ?>"
        />
    </td>
</tr>

<script>
    BX.ready(function () {
        BX.UI.Hint.init();
    })
</script>
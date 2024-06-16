<?php

use Bitrix\Main\Context;
use Bitrix\Main\Page\Asset;

class Events
{
    static function addMenuItem()
    {
        if (Context::getCurrent()->getRequest()->isAdminSection()) {
            return;
        }

        $js = <<<JS
            <script>
            BX.ready(function () {                
                const extraBtnBox = document.querySelector('.menu-extra-btn-box');
                const menuItem = document.createElement('div');
                menuItem.id = 'company_facts';
                menuItem.style.color = '#eaeff8';
                menuItem.style.cursor = 'pointer';
                menuItem.classList.add('menu-item-link');
                menuItem.innerHTML = '<span class="menu-item-link-text">Факты о компании</span>';
                menuItem.onclick = BX.Facts.showFacts;
                extraBtnBox.parentNode.insertBefore(menuItem, extraBtnBox);
            });
            </script>
JS;
        Asset::getInstance()->addString($js);
    }
}
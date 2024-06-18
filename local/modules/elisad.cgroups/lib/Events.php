<?php

namespace Elisad\Cgroups;

class Events
{
    public static function addMenuItem(&$items)
    {
        $items[] = array(
            'ID' => 'CGROUPS',
            'MENU_ID' => 'menu_crm_cgroups',
            'NAME' => 'Группы контактов',
            'TITLE' => 'Группы контактов',
            'URL' => '/crm/cgroups/'
        );
    }
}

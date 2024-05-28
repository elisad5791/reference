<?php

namespace Elisad\d7;

use Elisad\d7\DataTable;
use Bitrix\Main\Entity\Event;

class Main
{
    public static function get()
    {
        $result = DataTable::getList(['select' => ['*']]);
        $row = $result->fetch();
        print '<pre>';
        print_r($row);
        print '</pre>';
        return $row;
    }
}
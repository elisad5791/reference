<?php

namespace Elisad\d7;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;

class AuthorTable extends DataManager
{
    public static function getTableName()
    {
        return 'd7_authors';
    }
    public static function getMap()
    {
        return [
            new IntegerField('ID', ['primary' => true, 'autocomplete' => true]),
            new StringField('NAME', ['required' => true]),
            new StringField('LAST_NAME')
        ];
    }
}
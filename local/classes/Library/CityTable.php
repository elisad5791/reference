<?php
namespace Library;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DateField;
use Bitrix\Main\Entity\EntityError;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\Validator\RegExp;

class CityTable extends DataManager
{
    public static function getTableName()
    {
        return 'my_city';
    }

    public static function getUfId()
    {
        return 'MY_CITY';
    }

    public static function getObjectClass()
    {
        return City::class;
    }

    public static function getCollectionClass()
    {
        return Cities::class;
    }

    public static function getMap()
    {
        return [
            new IntegerField('ID', ['primary' => true, 'autocomplete' => true]),
            new StringField('TITLE', ['required' => true, 'validation' => fn() => [new RegExp('/^[А-Яа-я\s]+$/u')]])
        ];
    }
}

/*
$result = CityTable::getEntity()->compileDbTableStructureDump();

CREATE TABLE `my_city` (
    `ID` int NOT NULL AUTO_INCREMENT,
    `TITLE` varchar(255) NOT NULL,
    PRIMARY KEY(`ID`)
)
*/
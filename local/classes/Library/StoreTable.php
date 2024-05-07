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
use Bitrix\Main\ORM\Fields\Relations\OneToMany;

class StoreTable extends DataManager
{
    public static function getTableName()
    {
        return 'my_store';
    }

    public static function getUfId()
    {
        return 'MY_STORE';
    }

    public static function getObjectClass()
    {
        return Store::class;
    }

    public static function getCollectionClass()
    {
        return Stores::class;
    }

    public static function getMap()
    {
        $titleReg = new RegExp('/^[А-Яа-я\s]+$/u');
        $addressReg = new RegExp('/^[А-Яа-я\d,\.\s]+$/u');

        return [
            new IntegerField('ID', ['primary' => true, 'autocomplete' => true]),
            new StringField('TITLE', ['required' => true, 'validation' => fn() => [$titleReg]]),
            new StringField('ADDRESS', ['required' => true, 'validation' => fn() => [$addressReg]]),
            new OneToMany('BOOK_ITEMS', StoreBookTable::class, 'STORE')
        ];
    }
}

/*
$result = StoreTable::getEntity()->compileDbTableStructureDump();

CREATE TABLE `my_store` (
    `ID` int NOT NULL AUTO_INCREMENT,
    `TITLE` varchar(255) NOT NULL,
    `ADDRESS` varchar(255) NOT NULL,
    PRIMARY KEY(`ID`)
)
*/
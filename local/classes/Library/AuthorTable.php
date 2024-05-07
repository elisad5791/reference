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
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;

class AuthorTable extends DataManager
{
    public static function getTableName()
    {
        return 'my_author';
    }

    public static function getUfId()
    {
        return 'MY_AUTHOR';
    }

    public static function getObjectClass()
    {
        return Author::class;
    }

    public static function getCollectionClass()
    {
        return Authors::class;
    }

    public static function getMap()
    {
        return [
            new IntegerField('ID', ['primary' => true, 'autocomplete' => true]),
            new StringField('NAME', ['required' => true, 'validation' => fn() => [new RegExp('/^[А-Яа-я\s]+$/u')]]),
            (new ManyToMany('BOOKS', BookTable::class))->configureTableName('my_book_author')
        ];
    }
}

/*
$result = AuthorTable::getEntity()->compileDbTableStructureDump();

CREATE TABLE `my_author` (
    `ID` int NOT NULL AUTO_INCREMENT,
    `NAME` varchar(255) NOT NULL,
    PRIMARY KEY(`ID`)
)
*/
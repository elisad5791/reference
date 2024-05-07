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
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;

class PublisherTable extends DataManager
{
    public static function getTableName()
    {
        return 'my_publisher';
    }

    public static function getUfId()
    {
        return 'MY_PUBLISHER';
    }

    public static function getObjectClass()
    {
        return Publisher::class;
    }

    public static function getCollectionClass()
    {
        return Publishers::class;
    }

    public static function getMap()
    {
        $join = Join::on('this.CITY_ID', 'ref.ID');
        $validator = new RegExp('/^[А-Яа-я\s]+$/u');

        return [
            new IntegerField('ID', ['primary' => true, 'autocomplete' => true]),
            new StringField('TITLE', ['required' => true, 'validation' => fn() => [$validator]]),
            new IntegerField('CITY_ID'),
            new Reference('CITY', CityTable::class, $join),
            new OneToMany('BOOKS', BookTable::class, 'PUBLISHER')
        ];
    }
}

/*
$result = PublisherTable::getEntity()->compileDbTableStructureDump();

CREATE TABLE `my_publisher` (
    `ID` int NOT NULL AUTO_INCREMENT,
    `TITLE` varchar(255) NOT NULL,
    `CITY_ID` int NOT NULL,
    PRIMARY KEY(`ID`)
)
*/
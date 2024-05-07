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
use Bitrix\Main\Type\Date;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;

class BookTable extends DataManager
{
    public static function getTableName()
    {
        return 'my_book';
    }

    public static function getUfId()
    {
        return 'MY_BOOK';
    }

    public static function getObjectClass()
    {
        return Book::class;
    }

    public static function getCollectionClass()
    {
        return Books::class;
    }

    public static function getMap()
    {
        $join = Join::on('this.PUBLISHER_ID', 'ref.ID');
        
        return [
            new IntegerField('ID', ['primary' => true, 'autocomplete' => true]),
            new StringField('TITLE', ['required' => true, 'validation' => fn() => [new RegExp('/^[А-Яа-я\s]+$/u')]]),
            new DateField('PUBLISH_DATE', ['default_value' => new Date]),
            new StringField('ISBN', ['required' => true, 'validation' => fn() => [function ($value) {
                $clean = str_replace('-', '', $value);
                if (preg_match('/^\d{13}$/', $clean)) {
                    return true;
                } else {
                    return 'Код ISBN должен содержать 13 цифр.';
                }
            }]]),
            new IntegerField('PUBLISHER_ID'),
            new Reference('PUBLISHER', PublisherTable::class, $join),
            (new ManyToMany('AUTHORS', AuthorTable::class))->configureTableName('my_book_author'),
            new OneToMany('STORE_ITEMS', StoreBookTable::class, 'BOOK')
        ];
    }
}

/*
$result = BookTable::getEntity()->compileDbTableStructureDump();

CREATE TABLE `my_book` (
    `ID` int NOT NULL AUTO_INCREMENT,
    `TITLE` varchar(255) NOT NULL,
    `PUBLISH_DATE` date NOT NULL,
    `ISBN` varchar(255) NOT NULL,
    `PUBLISHER_ID` int NOT NULL,
    PRIMARY KEY(`ID`)
)

CREATE TABLE `my_book_author` (
    `BOOK_ID` int NOT NULL,
    `AUTHOR_ID` int NOT NULL,
    PRIMARY KEY(`BOOK_ID`, `AUTHOR_ID`)
)
*/
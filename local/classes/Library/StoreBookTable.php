<?php
namespace Library;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

class StoreBookTable extends DataManager
{
    public static function getTableName()
    {
        return 'my_store_book';
    }
    public static function getMap()
    {
        $join1 = Join::on('this.STORE_ID', 'ref.ID');
        $join2 = Join::on('this.BOOK_ID', 'ref.ID');

        return [
            (new IntegerField('STORE_ID'))->configurePrimary(true),
            new Reference('STORE', StoreTable::class, $join1),
            (new IntegerField('BOOK_ID'))->configurePrimary(true),
            new Reference('BOOK', BookTable::class, $join2),
            (new IntegerField('QUANTITY'))->configureDefaultValue(0)
        ];
    }
}

/*
$result = StoreTable::getEntity()->compileDbTableStructureDump();

CREATE TABLE `my_store_book` (
    `STORE_ID` int NOT NULL,
    `BOOK_ID` int NOT NULL,
    `QUANTITY` int NOT NULL,
    PRIMARY KEY(`STORE_ID`, `BOOK_ID`)
)
*/
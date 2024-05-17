<?php
namespace Stock;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DateField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Type\Date;
use Bitrix\Iblock\Elements\ElementProvidersTable;
use Bitrix\Iblock\Elements\ElementResponsibleTable;

class ProductTable extends DataManager
{
    public static function getTableName()
    {
        return 'c_product';
    }

    public static function getUfId()
    {
        return 'C_PRODUCT';
    }

    public static function getObjectClass()
    {
        return Product::class;
    }

    public static function getCollectionClass()
    {
        return Products::class;
    }

    public static function getMap()
    {
        $join1 = Join::on('this.PROVIDER_ID', 'ref.ID');
        $join2 = Join::on('this.RESPONSIBLE_ID', 'ref.ID');

        return [
            new IntegerField('ID', ['primary' => true, 'autocomplete' => true]),
            new StringField('TITLE'),
            new StringField('UNIT'),
            new IntegerField('QUANTITY'),
            new IntegerField('PRICE'),
            new DateField('RECEIPT_DATE', ['default_value' => new Date]),
            new IntegerField('PROVIDER_ID'),
            new Reference('PROVIDER', ElementProvidersTable::class, $join1),
            new IntegerField('RESPONSIBLE_ID'),
            new Reference('RESPONSIBLE', ElementResponsibleTable::class, $join2),
            new StringField('INVOICE', ['required' => true])
        ];
    }
}

/*
$result = ProductTable::getEntity()->compileDbTableStructureDump();

CREATE TABLE `c_product` (
    `ID` int NOT NULL AUTO_INCREMENT,
    `TITLE` varchar(255) NOT NULL,
    `UNIT` varchar(255) NOT NULL,
    `QUANTITY` int NOT NULL,
    `PRICE` int NOT NULL,
    `RECEIPT_DATE` date NOT NULL,
    `PROVIDER_ID` int NOT NULL,
    `RESPONSIBLE_ID` int NOT NULL,
    `INVOICE` varchar(255) NOT NULL,
    PRIMARY KEY(`ID`)
);
*/
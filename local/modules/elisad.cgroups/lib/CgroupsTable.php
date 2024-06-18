<?php
namespace Elisad\Cgroups;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\UserTable;

class CgroupsTable extends DataManager
{
    public static function getTableName()
    {
        return 'c_cgroups';
    }

    public static function getMap()
    {
        $join =  ['=this.ASSIGNED_BY_ID' => 'ref.ID'];

        return [
            new IntegerField('ID', ['primary' => true, 'autocomplete' => true]),
            new StringField('NAME'),
            new StringField('INTERESTS'),
            new IntegerField('ASSIGNED_BY_ID'),
            new ReferenceField('ASSIGNED_BY', UserTable::class, $join)
        ];
    }
}
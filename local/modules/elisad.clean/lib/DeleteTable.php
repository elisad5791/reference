<?php

namespace Elisad\Clean;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DateField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;

Loc::loadMessages(__FILE__);

class DeleteTable extends DataManager
{
    public static function getTableName()
    {
        return 'elisad_delete';
    }

    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('DELETE_ENTITY_ID_FIELD')
                ]
            ),
            new DateField(
                'DATE',
                [
                    'required' => true,
                    'title' => Loc::getMessage('DELETE_ENTITY_DATE_FIELD')
                ]
            ),
            new StringField(
                'TIP',
                [
                    'required' => true,
                    'title' => Loc::getMessage('DELETE_ENTITY_TIP_FIELD')
                ]
            ),
            new StringField(
                'PATH',
                [
                    'required' => true,
                    'title' => Loc::getMessage('DELETE_ENTITY_PATH_FIELD')
                ]
            ),
        ];
    }
}


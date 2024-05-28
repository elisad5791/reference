<?php

namespace Elisad\Clean;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\Type\DateTime;

Loc::loadMessages(__FILE__);

class FileTable extends DataManager
{
    public static function getTableName()
    {
        return 'b_file';
    }

    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                ['primary' => true, 'autocomplete' => true, 'title' => Loc::getMessage('FILE_ENTITY_ID_FIELD')]
            ),
            new DatetimeField(
                'TIMESTAMP_X',
                ['default' => new DateTime, 'title' => Loc::getMessage('FILE_ENTITY_TIMESTAMP_X_FIELD')]
            ),
            new StringField('MODULE_ID', ['title' => Loc::getMessage('FILE_ENTITY_MODULE_ID_FIELD')]),
            new IntegerField('HEIGHT', ['title' => Loc::getMessage('FILE_ENTITY_HEIGHT_FIELD')]),
            new IntegerField('WIDTH', ['title' => Loc::getMessage('FILE_ENTITY_WIDTH_FIELD')]),
            new IntegerField('FILE_SIZE', ['title' => Loc::getMessage('FILE_ENTITY_FILE_SIZE_FIELD')]),
            new StringField(
                'CONTENT_TYPE',
                ['default' => 'IMAGE', 'title' => Loc::getMessage('FILE_ENTITY_CONTENT_TYPE_FIELD')]
            ),
            new StringField('SUBDIR', ['title' => Loc::getMessage('FILE_ENTITY_SUBDIR_FIELD')]),
            new StringField(
                'FILE_NAME',
                ['required' => true, 'title' => Loc::getMessage('FILE_ENTITY_FILE_NAME_FIELD')]
            ),
            new StringField('ORIGINAL_NAME', ['title' => Loc::getMessage('FILE_ENTITY_ORIGINAL_NAME_FIELD')]),
            new StringField('DESCRIPTION', ['title' => Loc::getMessage('FILE_ENTITY_DESCRIPTION_FIELD')]),
            new StringField('HANDLER_ID', ['title' => Loc::getMessage('FILE_ENTITY_HANDLER_ID_FIELD')]),
            new StringField('EXTERNAL_ID', ['title' => Loc::getMessage('FILE_ENTITY_EXTERNAL_ID_FIELD')]),
        ];
    }
}
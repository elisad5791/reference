<?php

namespace Elisad\d7;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\TextField;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\Validator\Unique;
use Bitrix\Main\Application;

class DataTable extends DataManager
{
    public static function getTableName()
    {
        return 'd7_data';
    }

    public static function getMap()
    {
        return [
            new IntegerField('ID', ['primary' => true, 'autocomplete' => true]),
            new BooleanField('ACTIVE', ['values' => ['N', 'Y']]),
            new StringField('SITE', ['required' => true]),
            new StringField('LINK', ['required' => true]),
            new StringField('ALT_PICTURE', ['required' => true]),
            new TextField('EXCEPTIONS'),
            new DatetimeField('DATE', ['required' => true]),
            new EnumField('TARGET', ['values' => ['self', 'blank'], 'required' => true]),
            new IntegerField('AUTHOR_ID'),
            new ReferenceField('AUTHOR', AuthorTable::class, ['=this.AUTHOR_ID' => 'ref.ID']),
            new StringField('LINK_PICTURE', [
                'column_name' => 'LINK_PICTURE_CODE',
                'validation' => fn() => [
                    new Unique,
                    function ($value) {
                        if (strlen($value) > 100)
                            return 'Код LINK_PICTURE должен содержать не более 100 символов';
                        return true;
                    }
                ]
            ])
        ];
    }

    public static function onAfterAdd(Event $event)
    {
        DataTable::clearCache();
    }

    public static function onAfterUpdate(Event $event)
    {
        DataTable::clearCache();
    }

    public static function onAfterDelete(Event $event)
    {
        DataTable::clearCache();
    }

    public static function clearCache()
    {
        $taggedCache = Application::getInstance()->getTaggedCache();
        $taggedCache->clearByTag('d7');
    }
}
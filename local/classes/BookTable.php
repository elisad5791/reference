<?php
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\DateField;

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

    public static function getMap()
    {
        return [
            new IntegerField('ID', ['primary' => true, 'autocomplete' => true]),
            new StringField('ISBN', ['required' => true]),
            new StringField('TITLE'),
            new DateField('PUBLISH_DATE')
        ];
    }
}
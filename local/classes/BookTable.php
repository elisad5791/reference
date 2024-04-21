<?php
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\DateField;
use Bitrix\Main\Entity\Validator\RegExp;
use Bitrix\Main\Type\Date;

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
            new StringField('ISBN', ['required' => true, 'validation' => fn() => [function ($value) {
                $clean = str_replace('-', '', $value);
                if (preg_match('/^\d{13}$/', $clean)) {
                    return true;
                } else {
                    return 'Код ISBN должен содержать 13 цифр.';
                }
            }]]),
            new StringField('TITLE', ['validation' => fn() => [new RegExp('/^[\w\s]+$/')]]),
            new DateField('PUBLISH_DATE', ['default_value' => new Date])
        ];
    }
}
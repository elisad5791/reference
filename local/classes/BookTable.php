<?php
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\DateField;
use Bitrix\Main\Entity\Validator\RegExp;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\EntityError;
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
            new StringField('TITLE', ['validation' => fn() => [new RegExp('/^[\w\s]+$/')]]),
            new DateField('PUBLISH_DATE', ['default_value' => new Date]),
            new StringField('ISBN', ['required' => true, 'validation' => fn() => [function ($value) {
                $clean = str_replace('-', '', $value);
                if (preg_match('/^\d{13}$/', $clean)) {
                    return true;
                } else {
                    return 'Код ISBN должен содержать 13 цифр.';
                }
            }]])
        ];
    }

    public static function onBeforeAdd(Event $event)
    {
        $result = new EventResult;
        $data = $event->getParameter('fields');
        if (isset($data['ISBN'])) {
            $cleanIsbn = str_replace('-', '', $data['ISBN']);
            $result->modifyFields(['ISBN' => $cleanIsbn]);
        }
        return $result;
    }

    public static function onBeforeUpdate(Event $event)
    {
        $result = new EventResult;
        $data = $event->getParameter('fields');
        if (isset($data['ISBN'])) {
            $result->addError(new EntityError('Невозможно обновить запись'));
        }
        return $result;
    }
}
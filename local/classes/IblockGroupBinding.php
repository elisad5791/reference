<?php

use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Entity\Query;
use Bitrix\Main\GroupTable;

class IblockGroupBinding
{
    const USER_TYPE = 'iblockGroupBinding';

    public static function getUserTypeDescription(): array {
        return [
            'USER_TYPE_ID' => static::USER_TYPE,
            'USER_TYPE' => static::USER_TYPE,
            'CLASS_NAME' => __CLASS__,
            'DESCRIPTION' => 'Привязка к группе пользователей',
            'PROPERTY_TYPE' => PropertyTable::TYPE_NUMBER,
            'ConvertToDB' => [__CLASS__, 'convertToDB'],
            'ConvertFromDB' => [__CLASS__, 'convertFromDB'],
            'GetPropertyFieldHtml' => [__CLASS__, 'getPropertyFieldHtml'],
            'GetPropertyFieldHtmlMulty' => [__CLASS__, 'getPropertyFieldHtmlMulty'],
            'GetAdminListViewHTML' => [__CLASS__, 'getAdminListViewHTML'],
            'GetAdminListEditHTML' => [__CLASS__, 'getAdminListEditHTML'],
            'GetAdminListEditHTMLMulty' => [__CLASS__, 'getAdminListEditHTMLMulty'],
            'GetAdminFilterHTML' => [__CLASS__, 'getAdminFilterHTML'],
            'GetUIFilterProperty' => [__CLASS__, 'getUIFilterProperty'],
        ];
    }

    public static function convertToDB($arProperty, $value): array {
        return $value;
    }

    public static function convertFromDB($arProperty, $value, $format = ''): array {
        return $value;
    }

    protected static function getList(): array	{
        $query = new Query(GroupTable::getEntity());
        $query->setSelect(['ID', 'NAME']);
        $query->setFilter(['ACTIVE' => 'Y']);
        $result = $query->fetchAll();

        $groups = [];
        foreach ($result as $item) {
            $groups[] = ['ID' => $item['ID'], 'VALUE' => $item['NAME']];
        }

        return $groups;
    }

    protected static function getFieldHtml($elements, $userFieldData): string 
    {
        $multiple = $userFieldData['MULTIPLE'] == 'Y' ? 'multiple' : '';
        $brackets = $userFieldData['MULTIPLE'] == 'Y' ? '[]' : '';
        $name = $userFieldData['FIELD_NAME'] . $brackets;
        $html = "<select $multiple name='$name'>";

        if ($userFieldData['MANDATORY'] != 'Y') {
            $selected = empty(array_filter($elements, fn($el) => $el['SELECTED'] == 'Y')) ? 'selected' : '';
            $text = htmlspecialcharsbx(static::getEmptyCaption($userFieldData));
            $html .= "<option value='' $selected>$text</option>";
        }

        foreach ($elements as $element) {
            $value = $element['ID'];
            $selected = $element['SELECTED'] == 'Y' ? ' selected' : '';
            $text = $element['VALUE'];
            $html .=  "<option value='$value' $selected>$text</option>";
        }

        $html .= '</select>';

        return $html;
    }

    protected static function getEmptyCaption($arUserField): string 
    {
        return $arUserField['SETTINGS']['CAPTION_NO_VALUE'] ?: 'Группа не выбрана';
    }

    public static function getPropertyFieldHtml($userField, $value, $additionalParameters): string {
        $groups = static::getList();
        if (!$groups) {
            return '';
        }

        $groups = array_map(function ($el) use ($value) {
            if (!$value)
                $el['SELECTED'] = 'N';
            else {
                $el['SELECTED'] = $el['ID'] == $value['VALUE'] ? 'Y' : 'N';
            }
            return $el;
        }, $groups);

        $userFieldData = ['FIELD_NAME' => $additionalParameters['VALUE']] + $userField;
        return static::getFieldHtml($groups, $userFieldData);
    }

    public static function getPropertyFieldHtmlMulty(array $userField, $value, array $additionalParameters): string 
    {
        $groups = static::getList();
        if (!$groups) {
            return '';
        }

        $arr = array_column($value, 'VALUE');
        $groups = array_map(function ($el) use ($arr) {
            if (!$arr)
                $el['SELECTED'] = 'N';
            else {
                $el['SELECTED'] = in_array($el['ID'], $arr) ? 'Y' : 'N';
            }
            return $el;
        }, $groups);

        $userFieldData = ['FIELD_NAME' => $additionalParameters['VALUE']] + $userField;
        return static::getFieldHtml($groups, $userFieldData);
    }

    public static function getAdminListViewHTML(array $userField, ?array $additionalParameters): string	
    {
        static $cache = [];
        $empty_caption = '&nbsp;';
        $groups = '';
        
        if (!array_key_exists($additionalParameters['VALUE'], $cache)) {
            $groups = static::getList();
            if (!$groups) {
                return $empty_caption;
            }

            foreach ($groups as $group) {
                $cache[$group['ID']] = $group['VALUE'];
            }
        }
        
        if (!array_key_exists($additionalParameters['VALUE'], $cache)) {
            $cache[$additionalParameters['VALUE']] = $empty_caption;
        }
        
        return $cache[$additionalParameters['VALUE']];
    }

    public static function getAdminListEditHTML(array $userField, ?array $additionalParameters): string	
    {
        $groups = static::getList();
        if (!$groups) {
            return '';
        }

        $groups = array_map(function ($el) use ($additionalParameters) {
            $el['SELECTED'] = $el['ID'] == $additionalParameters['VALUE'] ? 'Y' : 'N';
            return $el;
        }, $groups);

        $userFieldData = ['FIELD_NAME' => $additionalParameters['NAME']] + $userField;
        return static::getFieldHtml($groups, $userFieldData);
    }

    public static function getAdminListEditHTMLMulty(array $userField, ?array $additionalParameters): string 
    {
        $groups = static::getList();
        if (!$groups) {
            return '';
        }

        $groups = array_map(function ($el) use ($additionalParameters) {
            $el['SELECTED'] = in_array($el['ID'], $additionalParameters['VALUE']) ? 'Y' : 'N';
            return $el;
        }, $groups);

        $userFieldData = ['FIELD_NAME' => $additionalParameters['NAME']] + $userField;
        return static::getFieldHtml($groups, $userFieldData);
    }

    public static function getAdminFilterHTML(array $userField, ?array $additionalParameters): string 
    {
        $groups = static::getList();
        if (!$groups) {
            return '';
        }

        $groups = array_map(function ($el) use ($additionalParameters) {
            $el['SELECTED'] = $el['ID'] == $additionalParameters['VALUE'] ? 'Y' : 'N';
            return $el;
        }, $groups);

        return static::getFieldHtml($groups, $userField);
    }

    public static function getUIFilterProperty($arProperty, $strHTMLControlName, &$fields): void 
    {
        $items = static::getList();
        $items = array_column($items, 'VALUE', 'ID');

        $fields['type'] = 'list';
        $fields['items'] = $items;
        $fields['operators'] = ['default' => '=', 'enum' => '@'];
        $fields['params'] = ['multiple' => 'Y'];
    }
}

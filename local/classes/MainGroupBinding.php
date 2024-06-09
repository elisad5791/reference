<?php

use Bitrix\Main\GroupTable;
use Bitrix\Main\Entity\Query;
use Bitrix\Main\UserField\Types\BaseType;

class MainGroupBinding extends BaseType
{
    const USER_TYPE_ID = 'MainGroupBinding';

    public static function getDbColumnType(): string
    {
        global $DB;
        switch (strtolower($DB->type)) {
            case 'mysql':
                return 'int(18)';
            case 'oracle':
                return 'number(18)';
            case 'mssql':
                return 'int';
        }
        return 'int';
    }

    protected static function getDescription(): array
    {
        return [
            'DESCRIPTION' => 'Привязка к группам пользователей',
            'BASE_TYPE' => CUserTypeManager::BASE_TYPE_INT,
            'USER_TYPE_ID' => static::USER_TYPE_ID,
        ];
    }

    protected static function getList(): array
    {
        $groups = [];

        $query = new Query(GroupTable::getEntity());
        $query->setSelect(['ID', 'NAME',]);
        $query->setFilter(['ACTIVE' => 'Y']);
        $result = $query->fetchAll();

        foreach ($result as $item) {
            $groups[] = ['ID' => $item['ID'], 'VALUE' => $item['NAME']];
        }

        return $groups;
    }

    protected static function getEmptyCaption($arUserField): string
    {
        return $arUserField['SETTINGS']['CAPTION_NO_VALUE'] ?: 'Группа не выбрана';
    }

    protected static function getFieldHtml($elements, $userFieldData): string
    {
        $multiple = $userFieldData['MULTIPLE'] == 'Y' ? 'multiple' : '';
        $brackets = $userFieldData['MULTIPLE'] == 'Y' ? '[]' : '';
        $name = $userFieldData['FIELD_NAME'] . $brackets;
        $disabled = $userFieldData['EDIT_IN_LIST'] != 'Y' ? "disabled='disabled'" : '';
        $html = "<select $multiple name='$name' $disabled>";

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

    public static function getEditFormHTML(array $userField, ?array $additionalParameters): string
    {
        $groups = static::getList();
        if (!$groups) {
            return '';
        }
        $groups = array_map(function ($el) use ($userField, $additionalParameters) {
            if ($userField['MULTIPLE'] == 'Y') {
                $el['SELECTED'] = in_array($el['ID'], $additionalParameters['VALUE']) ? 'Y' : 'N';
            }
            else {
                $el['SELECTED'] = $el['ID'] == $additionalParameters['VALUE'] ? 'Y' : 'N';
            }
            return $el;
        }, $groups);

        return static::getFieldHtml($groups, $userField);
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

    public static function getAdminListEditHTML(array $userField, ?array $additionalParameters):string
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

    public static function getAdminListEditHTMLMulty(array $userField, ?array $additionalParameters): string {
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

    public static function getFilterHTML(array $userField, ?array $additionalParameters): string
    {
        if (!is_array($additionalParameters['VALUE'])) {
            $additionalParameters['VALUE'] = [];
        }
        $groups = static::getList();

        $groups = array_map(function ($el) use ($additionalParameters) {
            $el['SELECTED'] = in_array($el['ID'], $additionalParameters['VALUE']) ? 'Y' : 'N';
            return $el;
        }, $groups);

        $userFieldData = ['FIELD_NAME' => $additionalParameters['NAME'], 'MULTIPLE' => 'Y'] + $userField;
        return static::getFieldHtml($groups, $userFieldData);
    }

    public static function getFilterData(array $userField, array $params): array
    {
        $groups = static::getList();
        $items = array_column($groups, 'VALUE', 'ID');

        return [
            'id' => $params['ID'],
            'name' => $params['NAME'],
            'type' => 'list',
            'items' => $items,
            'params' => ['multiple' => 'Y'],
            'filterable' => ''
        ];
    }
}
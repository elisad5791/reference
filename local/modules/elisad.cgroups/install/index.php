<?php

use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\UrlRewriter;
use Elisad\Cgroups\CgroupsTable;
use Bitrix\Main\IO\Directory;

class Elisad_Cgroups extends CModule
{
    public $MODULE_ID;
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;
    public $SHOW_SUPER_ADMIN_GROUP_RIGHTS;
    public $MODULE_GROUP_RIGHTS;
    public $errors;

    function __construct()
    {
        $arModuleVersion = [];
        include_once(__DIR__ . '/version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_ID = 'elisad.cgroups';
        $this->MODULE_NAME = 'Группы контактов';
        $this->MODULE_DESCRIPTION = 'Создание групп контактов по интересам';
        $this->PARTNER_NAME = 'Elisad';
        $this->PARTNER_URI = 'https://elisad.ru';
        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = 'Y';
        $this->MODULE_GROUP_RIGHTS = 'Y';
    }

    function DoInstall()
    {
        ModuleManager::RegisterModule('elisad.cgroups');
        Loader::includeModule('elisad.cgroups');
        $this->InstallDB();
        $this->addData();
        $this->InstallFiles();
        $this->InstallEvents();
    }

    function DoUninstall()
    {
        Loader::includeModule('elisad.cgroups');
        $this->UnInstallDB();
        $this->UnInstallFiles();
        $this->UnInstallEvents();
        ModuleManager::UnRegisterModule('elisad.cgroups');
    }

    function InstallDB()
    {
        $connection = Application::getConnection();
        $entity = CgroupsTable::getEntity();
        $table = $entity->getDBTableName();
        if (!$connection->isTableExists($table)) {
            $entity->createDbTable();
        }
    }

    function addData()
    {
        $item1 = [
            'ID' => 1,
            'NAME' => 'Телефоны',
            'INTERESTS' => 'Apple,Nokia,Samsung,Motorola,LG',
            'ASSIGNED_BY_ID' => 1
        ];
        $item2 = [
            'ID' => 2,
            'NAME' => 'Телевизоры',
            'INTERESTS' => 'LG,Samsung,Sony',
            'ASSIGNED_BY_ID' => 1
        ];
        $item3 = [
            'ID' => 3,
            'NAME' => 'Ноутбуки',
            'INTERESTS' => 'ASUS,Lenovo,MacBook,Dell,HP',
            'ASSIGNED_BY_ID' => 1
        ];
        $item4 = [
            'ID' => 4,
            'NAME' => 'Наушники',
            'INTERESTS' => 'ASUS,Lenovo,MacBook,Dell,HP',
            'ASSIGNED_BY_ID' => 1
        ];
        $item5 = [
            'ID' => 5,
            'NAME' => 'Динамики',
            'INTERESTS' => 'ASUS,Lenovo,MacBook,Dell,HP',
            'ASSIGNED_BY_ID' => 1
        ];
        $item6 = [
            'ID' => 6,
            'NAME' => 'Зарядные устройства',
            'INTERESTS' => 'ASUS,Lenovo,MacBook,Dell,HP',
            'ASSIGNED_BY_ID' => 1
        ];
        $items = [$item1, $item2, $item3, $item4, $item5, $item6];

        foreach ($items as $item) {
            CgroupsTable::add($item);
        }
    }

    function UnInstallDB()
    {
        $connection = Application::getConnection();
        $entity = CgroupsTable::getEntity();
        $table = $entity->getDBTableName();
        if ($connection->isTableExists($table)) {
            $connection->dropTable($table);
        }
    }

    function installFiles()
    {
        $fromComp = __DIR__ . '/components/';
        $toComp = $_SERVER['DOCUMENT_ROOT'] . '/local/components';
        CopyDirFiles($fromComp, $toComp, true, true);

        $fromPages = __DIR__ . '/pages/';
        $toPages = $_SERVER['DOCUMENT_ROOT'] . '/crm';
        CopyDirFiles($fromPages, $toPages, true, true);

        $params = [
            'CONDITION' => '#^/crm/cgroups/#',
            'RULE' => '',
            'ID' => 'elisad.cgroups',
            'PATH' => '/crm/cgroups/index.php',
        ];
        UrlRewriter::add('s1', $params);
    }

    function unInstallFiles()
    {
        $cgroups = $_SERVER['DOCUMENT_ROOT'] . '/local/components/elisad/cgroups';
        Directory::deleteDirectory($cgroups);

        $list = $_SERVER['DOCUMENT_ROOT'] . '/local/components/elisad/cgroups.list';
        Directory::deleteDirectory($list);

        $show = $_SERVER['DOCUMENT_ROOT'] . '/local/components/elisad/cgroups.show';
        Directory::deleteDirectory($show);

        $pages = $_SERVER['DOCUMENT_ROOT'] . '/crm/cgroups';
        Directory::deleteDirectory($pages);

        $elisad = $_SERVER['DOCUMENT_ROOT'] . '/local/components/elisad';
        $elisadEmpty = count(glob($elisad . '/*')) ? false : true;
        if ($elisadEmpty) {
            Directory::deleteDirectory($elisad);
        }

        $components = $_SERVER['DOCUMENT_ROOT'] . '/local/components';
        $componentsEmpty = count(glob($components . '/*')) ? false : true;
        if ($componentsEmpty) {
            Directory::deleteDirectory($components);
        }

        $params = [
            'ID' => 'elisad.cgroups',
            'PATH' => '/crm/cgroups/index.php',
        ];
        UrlRewriter::delete('s1', $params);
    }

    function InstallEvents()
    {
        $manager = EventManager::getInstance();
        $event = 'OnAfterCrmControlPanelBuild';
        $class = 'Elisad\Cgroups\Events';
        $method = 'addMenuItem';
        $manager->registerEventHandler('crm', $event, $this->MODULE_ID, $class, $method);
    }

    function UnInstallEvents()
    {
        $manager = EventManager::getInstance();
        $event = 'OnAfterCrmControlPanelBuild';
        $class = 'Elisad\Cgroups\Events';
        $method = 'addMenuItem';
        $manager->unRegisterEventHandler('crm', $event, $this->MODULE_ID, $class, $method);
    }
}
<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;
use Elisad\d7\AuthorTable;
use Elisad\d7\DataTable;
use Bitrix\Main\Type;

Loc::loadMessages(__FILE__);

class Elisad_D7 extends CModule
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
        $this->MODULE_ID = 'elisad.d7';
        $this->MODULE_NAME = 'Пример модуля D7';
        $this->MODULE_DESCRIPTION = 'Тестовый модуль для разработчиков';
        $this->PARTNER_NAME = 'Эйч Маркетинг';
        $this->PARTNER_URI = 'https://hmarketing.ru';
        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = 'Y';
        $this->MODULE_GROUP_RIGHTS = 'Y';
    }

    function DoInstall()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        global $APPLICATION;

        if ($request['step'] < 2) {
            $path = __DIR__ . '/installInfo-step1.php';
            $APPLICATION->IncludeAdminFile(Loc::getMessage('INSTALL_TITLE_STEP_1'), $path);
        }

        if ($request['step'] == 2) {
            ModuleManager::RegisterModule('elisad.d7');
            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();
            $this->installAgents();
            if ($request['add_data'] == 'Y') {
                $this->addData();
            }
            $path = __DIR__ . '/installInfo-step2.php';
            $APPLICATION->IncludeAdminFile(Loc::getMessage('INSTALL_TITLE_STEP_2'), $path);
        }

        return true;
    }

    function DoUninstall()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        global $APPLICATION;

        if ($request['step'] < 2) {
            $path = __DIR__ . '/deinstallInfo-step1.php';
            $APPLICATION->IncludeAdminFile(Loc::getMessage('DEINSTALL_TITLE_1'), $path);
        }

        if ($request['step'] == 2) {
            if ($request['save_data'] == 'Y') {
                $this->UnInstallDB();
            }
            $this->UnInstallEvents();
            $this->UnInstallFiles();
            $this->unInstallAgents();
            ModuleManager::UnRegisterModule('elisad.d7');
            $path = __DIR__ . '/deinstallInfo-step2.php';
            $APPLICATION->IncludeAdminFile(Loc::getMessage('DEINSTALL_TITLE_2'), $path);
        }

        return true;
    }

    function InstallDB()
    {
        Loader::includeModule($this->MODULE_ID);
        $conn = Application::getConnection();
        $isDatatableExists = $conn->isTableExists(Base::getInstance(DataTable::class)->getDBTableName());
        $isAuthortableExists = $conn->isTableExists(Base::getInstance(AuthorTable::class)->getDBTableName());

        if (!$isDatatableExists) {
            Base::getInstance(DataTable::class)->createDbTable();
        }

        if (!$isAuthortableExists) {
            Base::getInstance(AuthorTable::class)->createDbTable();
        }
    }

    function UnInstallDB()
    {
        Loader::includeModule($this->MODULE_ID);
        $conn = Application::getConnection();
        $conn->queryExecute('DROP TABLE IF EXISTS ' . Base::getInstance(DataTable::class)->getDBTableName());
        $conn->queryExecute('DROP TABLE IF EXISTS ' . Base::getInstance(AuthorTable::class)->getDBTableName());
        Option::delete($this->MODULE_ID);
    }

    function InstallEvents()
    {
        $manager = EventManager::getInstance();

        $event1 = 'OnSomeEvent';
        $class1 = '\Elisad\d7\Main';
        $method1 = 'get';
        $manager->registerEventHandler($this->MODULE_ID, $event1, $this->MODULE_ID, $class1, $method1);

        $event2 = '\Elisad\d7\DataTable::OnBeforeUpdate';
        $class2 = '\Elisad\d7\Events';
        $method2 = 'eventHandler';
        $manager->registerEventHandler($this->MODULE_ID, $event2, $this->MODULE_ID, $class2, $method2);

        return true;
    }

    function UnInstallEvents()
    {
        $manager = EventManager::getInstance();

        $event1 = 'OnSomeEvent';
        $class1 = '\Elisad\d7\Main';
        $method1 = 'get';
        $manager->unRegisterEventHandler($this->MODULE_ID, $event1, $this->MODULE_ID, $class1, $method1);

        $event2 = '\Elisad\d7\DataTable::OnBeforeUpdate';
        $class2 = '\Elisad\d7\Events';
        $method2 = 'eventHandler';
        $manager->unRegisterEventHandler($this->MODULE_ID, $event2, $this->MODULE_ID, $class2, $method2);

        return true;
    }

    function InstallFiles()
    {
        CopyDirFiles(__DIR__ . '/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin', true, true);
        CopyDirFiles(__DIR__ . '/files', $_SERVER['DOCUMENT_ROOT'] . '/', true, true);
        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFiles(__DIR__ . '/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin');
        DeleteDirFiles(__DIR__ . '/files', $_SERVER['DOCUMENT_ROOT'] . '/');
        return true;
    }

    function addData()
    {
        Loader::includeModule($this->MODULE_ID);

        $data = [
            'ACTIVE' => 'N',
            'SITE' => 's1',
            'LINK' => 'https://google.com',
            'LINK_PICTURE' => '/bitrix/components/elisad.d7/banner.jpg',
            'ALT_PICTURE' => 'alt',
            'EXCEPTIONS' => 'exceptions',
            'DATE' => new Type\DateTime(date('d.m.Y H:i:s')),
            'TARGET' =>  'self',
            'AUTHOR_ID' =>  1
        ];
        DataTable::add($data);

        $author = [
            'NAME' => 'Иван',
            'LAST_NAME' => 'Иванов'
        ];
        AuthorTable::add($author);

        return true;
    }

    function installAgents()
    {
        CAgent::AddAgent('\Elisad\d7\Agent::timeAgent();', $this->MODULE_ID, 'N', 10);
    }

    function unInstallAgents()
    {
        CAgent::RemoveModuleAgents($this->MODULE_ID);
    }
}

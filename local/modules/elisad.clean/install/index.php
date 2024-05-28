<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class Elisad_Clean extends CModule
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
        $this->MODULE_ID = 'elisad.clean';
        $this->MODULE_NAME = 'Очистка папки';
        $this->MODULE_DESCRIPTION = 'Очистка папки /upload/iblock/';
        $this->PARTNER_NAME = 'Elisad';
        $this->PARTNER_URI = 'https://elisad.ru';
        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = 'Y';
        $this->MODULE_GROUP_RIGHTS = 'Y';
    }

    function DoInstall()
    {
        ModuleManager::RegisterModule('elisad.clean');
        $this->InstallDB();

        $path = __DIR__ . '/step.php';
        global $APPLICATION;
        $APPLICATION->IncludeAdminFile(Loc::getMessage('INSTALL_TITLE'), $path);

        return true;
    }

    function DoUninstall()
    {
        $this->UnInstallDB();
        ModuleManager::UnRegisterModule('elisad.clean');

        $path = __DIR__ . '/unstep.php';
        global $APPLICATION;
        $APPLICATION->IncludeAdminFile(Loc::getMessage('DEINSTALL_TITLE'), $path);

        return true;
    }

    function InstallDB()
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/elisad.clean/install/db/install.sql';
        $this->errors = false;
        global $DB;
        $this->errors = $DB->RunSQLBatch($path);
        if ($this->errors) {
            return $this->errors;
        }
        return true;
    }

    function UnInstallDB()
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/elisad.clean/install/db/uninstall.sql';
        $this->errors = false;
        global $DB;
        $this->errors = $DB->RunSQLBatch($path);
        if ($this->errors) {
            return $this->errors;
        }
        return true;
    }
}

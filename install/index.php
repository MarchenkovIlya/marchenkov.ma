<?php

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\UrlRewriter;
use marchenkov\ma\Handler\ButtonSchedule;

Class marchenkov_ma extends CModule {

    const MENU_ITEM_ID = 'marchenkov_ma_menu_item';
    var $MODULE_ID = "marchenkov.ma";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    function __construct()
    {
        $arModuleVersion = array();
        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
        $this->MODULE_NAME = "ManagersAchievements – модуль с компонентом";
        $this->MODULE_DESCRIPTION = "После установки вы сможете настраивать достижения менеджеров";
    }
    function InstallFiles()
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/marchenkov.ma/install/files/components/",
            $_SERVER["DOCUMENT_ROOT"]."/local/components/", true, true);
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/marchenkov.ma/install/files/js/",
            $_SERVER["DOCUMENT_ROOT"]."/local/js/", true, true);
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/marchenkov.ma/install/public/",
            $_SERVER["DOCUMENT_ROOT"]."/", true, true);

        try {
            UrlRewriter::add('s1', [
                'ID' => 'marchenkov.ma:managersachievements',
                'CONDITION' => '#^/achievements/#',
                'PATH' => '/achievements/index.php'
            ]);
        } catch (ArgumentNullException) {
            // Noop, never happens because $siteId is a string literal.
        }


    }
    function UnInstallFiles()
    {
        DeleteDirFilesEx("/local/components/marchenkov.ma");
        DeleteDirFilesEx("/local/js/marchenkov.ma");
        return true;
    }
    function DoInstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        $this->InstallFiles();
        $this->installEventHandlers();
        $this->createItemMenu();
        RegisterModule("marchenkov.ma");
        $APPLICATION->IncludeAdminFile("Установка модуля marchenkov.ma", $DOCUMENT_ROOT."/local/modules/marchenkov.ma/install/step.php");
    }
    function DoUninstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        $this->UnInstallFiles();
        $this->UnInstallEventHandlers();
        UnRegisterModule("marchenkov.ma");
        $APPLICATION->IncludeAdminFile("Деинсталляция модуля marchenkov.ma", $DOCUMENT_ROOT."/local/modules/marchenkov.ma/install/unstep.php");
    }

    private function installEventHandlers() {
        $eventManager = EventManager::getInstance();

        $eventManager->registerEventHandlerCompatible(
            'main',
            'OnEpilog',
            'marchenkov.ma',
            ButtonSchedule::class,
            'addedButton'
        );

    }

    private function UnInstallEventHandlers() {
        $eventManager = EventManager::getInstance();

        $eventManager->unRegisterEventHandler(
            'main',
            'OnEpilog',
            'marchenkov.ma',
            ButtonSchedule::class,
            'addedButton'
        );
    }
}
?>
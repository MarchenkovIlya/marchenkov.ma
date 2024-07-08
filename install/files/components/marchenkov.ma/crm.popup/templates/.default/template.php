<?php

/**
 * @author Ilya Marchenkov <im@intervolga.ru>
 */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\Extension;

defined('B_PROLOG_INCLUDED') || die;

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CrmPopup $component */
Extension::load(array("ui.forms"));
?>

<form id="select-user-form" method="post">
    <div class="ui-entity-editor-block-title ui-entity-widget-content-block-title-edit" style="padding-top: 20px">
        <label class="ui-entity-editor-block-title-text"><?= Loc::getMessage('MARCHENKOV_MA_SELECT_USER_INPUT') ?></label>
    </div>
    <br>
    <div class="ui-ctl ui-ctl-textbox" id="container" style="margin-top: -8px">
        <div id="chats-selector-container" style="width: 500px">
    </div>

    <div class="ui-entity-editor-block-title ui-entity-widget-content-block-title-edit" style="padding-top: 20px">
        <label for="message" class="ui-entity-editor-block-title-text"><?= Loc::getMessage('MARCHENKOV_MA_TEXT_INPUT') ?></label>
    </div>
    <br>
    <div class="ui-ctl ui-ctl-textarea" style="max-width: 500px">
        <textarea class="ui-ctl-element" id="message" name="message"></textarea>
    </div>
</form>




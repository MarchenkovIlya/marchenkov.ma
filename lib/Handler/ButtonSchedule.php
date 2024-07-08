<?php

namespace marchenkov\ma\Handler;

use Bitrix\Main\Application;
use Bitrix\Main\UI\Extension;
use Bitrix\UI\Buttons\Color;
use Bitrix\UI\Buttons\CreateButton;
use Bitrix\UI\Buttons\JsCode;
use CComponentEngine;

class ButtonSchedule {
    static function addedButton() {
        global $APPLICATION;
        Extension::load(['marchenkov.ma.crm']);

        $variables = array();
        $templates = array(
            'deal' => 'crm/deal/details/#deal_id#/',
            'lead' => 'crm/lead/details/#lead_id#/',
            'contact' => 'crm/contact/details/#contact_id#/',
            'company' => 'crm/company/details/#company_id#/',
        );

        $result = CComponentEngine::parseComponentPath('/', $templates, $variables);

        if ($result === 'deal' || $result === 'lead' || $result === 'contact' || $result === 'company') {

        $button = new CreateButton([
                'id' => 'sendEntityButton',
                'text' => 'Поделиться',
                'color'  => Color::SUCCESS,
        ]);

        $button->bindEvents([
                'click' => new JsCode(
                        "BX.marchenkov.ma.crm.Popup.create().bind();"
                )
        ]);

        $button->addAttribute('style', 'margin-left: 10px');
        $content = $button->render();

        $APPLICATION->AddViewContent('inside_pagetitle', $content, 10100);
        }
    }
}


<?php

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\Response\Component;

class CrmPopup extends CBitrixComponent implements Controllerable {

    public function onPrepareComponentParams($arParams) {
        return $arParams;
    }

    public function executeComponent() {
        $this->includeComponentTemplate();
    }

    protected function listKeysSignedParameters(): array {
        return [];
    }

    public function viewTemplateForSendingAction($template): Component {
        return new Component(
                'marchenkov.ma:crm.popup',
                $template,
                []
        );
    }

    public function configureActions(): array {
        return [];
    }
}
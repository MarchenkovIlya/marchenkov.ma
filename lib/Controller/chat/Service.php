<?php

namespace marchenkov\ma\controller\chat;

use Bitrix\Im\Bot\Keyboard;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Loader;
use CIMMessenger;

class Service extends Controller
{

    public function __construct() {
        parent::__construct();

    }
    public function sendMessageAction(array $userIds, string $message, string $pathname): bool {
        global $USER;
        $this->includeModules();
        $keyboard = new Keyboard(5, ['#fff']);
        $keyboard->addButton([
            'DISPLAY' => 'LINE',
            'TEXT' => 'Просмотреть',
            'BG_COLOR' => '#29619b',
            'TEXT_COLOR' => '#fff',
            'BLOCK' => 'Y',
            'LINK' => $pathname,
        ]);

        if (empty($message)) {
            $message = 'С вами поделились';
        }

        foreach ($userIds as $userId) {
            $param = [
                'DIALOG_ID' => $userId,
                'TO_USER_ID' => $userId,
                'FROM_USER_ID' => $USER->GetID(),
                'MESSAGE' => $message,
                'MESSAGE_TYPE' => 'C',
                'KEYBOARD' => $keyboard,
                'SYSTEM' => 'N',
                'URL_PREVIEW' => 'N',
            ];
            $results = CIMMessenger::add($param);
            if (!$results) {
                return false;
            }
        }
        return true;
    }

    private function includeModules():void {
        Loader::includeModule('im');
        Loader::includeModule('imbot');
    }
}
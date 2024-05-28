<?php

namespace Elisad\d7;

use \Bitrix\Main\Entity\Event;

class Events
{
    static public function eventHandler(Event $event)
    {
        $fields = $event->getParameter('fields');
        echo '<pre>';
        echo 'Обработчик события';
        var_dump($fields);
        echo '</pre>';
    }
}
<?php

namespace Flash\Bundle\DefaultBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserEventFactory {

    public static function get($eventName, $acc) {

        $userEvent = new \Flash\Bundle\DefaultBundle\Entity\UserEvent();

        switch ($eventName) {
            case 'new_user':
                $userEvent->setTitle($acc->getUsername() . ' только что присоеденился к ресурсу.');
                $userEvent->setDescription('Поздравляем с регистрацией!');
                $userEvent->setAccount($acc);
                break;
            case 'add_event':
                $userEvent->setTitle($acc->getUsername() . ' создал в группе новое событие.');
                $userEvent->setDescription('Парам-пам-пам!');
                $userEvent->setAccount($acc);
                break;
            default :
                break;
        }
        return $userEvent;
    }

}

?>

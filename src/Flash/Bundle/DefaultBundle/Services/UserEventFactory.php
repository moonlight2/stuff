<?php

namespace Flash\Bundle\DefaultBundle\Services;

class UserEventFactory {

    public static function get($eventName, $acc, $obj=null) {

        $userEvent = new \Flash\Bundle\DefaultBundle\Entity\UserEvent();

        switch ($eventName) {
            case 'new_user':
                $userEvent->setTitle($acc->getFirstName() . " " . $acc->getLastName() . ' только что присоеденился к ресурсу.');
                $userEvent->setDescription('Поздравляем с регистрацией!');
                $userEvent->setAccount($acc);
                break;
            case 'add_event':
                $userEvent->setTitle($acc->getUsername() . ' создал в группе новое событие.');
                $userEvent->setDescription('Парам-пам-пам!');
                $userEvent->setAccount($acc);
                break;
            case 'new_group':
                $userEvent->setTitle($acc->getUsername() . ' создал группу '. $obj->getName());
                $userEvent->setDescription('Поздравляем с новой группой!');
                $userEvent->setAccount($acc);
                break;
            default :
                break;
        }
        return $userEvent;
    }

}

?>

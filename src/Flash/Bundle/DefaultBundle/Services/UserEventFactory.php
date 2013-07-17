<?php

namespace Flash\Bundle\DefaultBundle\Services;

class UserEventFactory {

    
    const NEW_USER = 'new_user';
    const NEW_PHOTO = 'new_photo';

    public static function get($eName, \Symfony\Component\Security\Core\User\UserInterface $acc, $obj = null) {

        $uEvent = new \Flash\Bundle\DefaultBundle\Entity\UserEvent();

        switch ($eName) {
            case 'new_user':
                $uEvent->setTitle(
                        "<a href='p" . $acc->getId() . "'>"
                        . $acc->getFirstName() . " " . $acc->getLastName() . "</a>" .
                        ' только что присоеденился к ресурсу.'
                );
                $uEvent->setDescription('Поздравляем с регистрацией!  Пригласите его в свою группу.');
                $uEvent->setAccount($acc);
                break;
            case 'add_event':
                //$uEvent->setTitle(' создал в группе новое событие.');
                //$uEvent->setDescription('Парам-пам-пам!');
                //$uEvent->setAccount($acc);
                break;
            case 'new_photo':
                
                $uEvent->setTitle($acc->getFirstName() . ' Добавил новое фото ');
                $uEvent->setDescription('Вот оно фото <br /><img src="image/thumb/'.$acc->getId().'/'. $obj->getPath() . '" />');
                $uEvent->setAccount($acc);
                break;
            default :
                break;
        }
        return $uEvent;
    }

}

?>

<?php

namespace Flash\Bundle\DefaultBundle\Services\Entity;

use FOS\RestBundle\View\View;
use Flash\Bundle\DefaultBundle\Form\EventType;
use Flash\Bundle\DefaultBundle\Services\CommonService;


class EventService extends CommonService {


    public function processForm($event) {

        $request = $this->injector->getRequest();
        $em = $this->injector->getDoctrine()->getManager();
        $form = $this->injector->getForm()->create(new EventType(), $event);
        $view = View::create();

        $form->bind($this->getFromRequest(array('name', 'description', 'city', 'country', 'date')));

        if ($form->isValid()) {

            $acc = $this->context->getToken()->getUser();
            $group = $acc->getGroup();
            $event->setGroup($group);

            $em->persist($event);
            $em->persist($group);
            $em->flush();

            if ($request->getMethod() == 'POST') {
                $this->injector->getAcl()->setOwnerForEntity($event);
            }
        } else {
            $view->setStatusCode(400);
            return $view->setData($this->getErrorMessages($form));
        }
        return $event;
    }

}

?>
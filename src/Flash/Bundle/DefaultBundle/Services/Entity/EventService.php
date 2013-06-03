<?php

namespace Flash\Bundle\DefaultBundle\Services\Entity;

use FOS\RestBundle\View\View;
use Flash\Bundle\DefaultBundle\Form\EventType;
use Flash\Bundle\DefaultBundle\Form\CalendarEventType;
use Flash\Bundle\DefaultBundle\Services\CommonService;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

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

//            if ($request->getMethod() == 'POST') {
//                $this->injector->getAcl()->setAuthorForEntity($event);
//            }
        } else {
            $view->setStatusCode(400);
            return $view->setData($this->getErrorMessages($form));
        }
        return $event;
    }

    public function processCalendarEventForm($event) {

        $request = $this->injector->getRequest();
        $em = $this->injector->getDoctrine()->getManager();
        $form = $this->injector->getForm()->create(new CalendarEventType(), $event);
        $view = View::create();

        $start = new \DateTime($request->get('start'));
        $end = new \DateTime($request->get('end'));


        $form->bind(array(
            'title' => $request->get('title'),
            'text' => $request->get('text'),
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $end->format('Y-m-d H:i:s'),
        ));

        if ($form->isValid()) {

            $acc = $this->context->getToken()->getUser();

            $acc->addCalendarEvent($event);

            $em->persist($event);
            $em->persist($acc);
            $em->flush();

            if ($request->getMethod() == 'POST') {
                $acl = $this->injector->getAcl();
                $acl->grant($event, MaskBuilder::MASK_EDIT);
                $acl->grant($event, MaskBuilder::MASK_DELETE);
            }
        } else {
            $view->setStatusCode(400);
            return $view->setData($this->getErrorMessages($form));
        }
        return $view->setData($event);
    }

    private function validateDate($date) {
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z$/', $date, $parts) == true) {
            $time = gmmktime($parts[4], $parts[5], $parts[6], $parts[2], $parts[3], $parts[1]);

            $input_time = strtotime($date);
            if ($input_time === false)
                return false;

            return $input_time == $time;
        } else {
            return false;
        }
    }

}

?>
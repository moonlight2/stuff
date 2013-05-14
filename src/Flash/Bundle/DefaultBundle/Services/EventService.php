<?php

namespace Flash\Bundle\DefaultBundle\Services;

use Symfony\Component\Security\Core\SecurityContext;
use FOS\RestBundle\View\View;
use Flash\Bundle\DefaultBundle\Services\RequestInjector;
use Flash\Bundle\DefaultBundle\Form\EventType;

class EventService extends CommonService {

    private $context;
    private $em;
    private $injector;

    public function __construct(SecurityContext $context, RequestInjector $injector) {

        $this->context = $context;
        $this->injector = $injector;
    }

    public function getRequest() {
        
           print_r($this->context->getToken()->getUser());
           exit();

//        $event = new \Flash\Bundle\DefaultBundle\Entity\Event();
//        $form = $this->injector->getForm()->create(new \Flash\Bundle\DefaultBundle\Form\EventType(), $event);
//
//        $form->bind($this->getFromRequest($request, array('name', 'description', 'city', 'country', 'date')));
//        if ($form->isValid()) {
//            echo "Valid";
//        } else {
//            print_r($this->getErrorMessages($form));
//        }
    }

    public function processForm($event) {

        $request = $this->injector->getRequest();
        $em = $this->injector->getDoctrine()->getManager();
        $form = $this->injector->getForm()->create(new EventType(), $event);
        $view = View::create();

        $form->bind($this->getFromRequest($request, array('name', 'description', 'city', 'country', 'date')));

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
<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Flash\Bundle\DefaultBundle\Entity\Event;
use Flash\Bundle\DefaultBundle\Form\EventType;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;
use FOS\RestBundle\View\View;

/**
 * @Route("/logged/rest/api/events")
 */
class EventApiController extends RESTController implements GenericRestApi {

    /**
     * @Route("/country/{country_id}/city/{city_id}")
     * @Method({"GET"})
     * @return single Account data
     */
    public function getEventsByLocation($country_id, $city_id) {
        
    }

    /**
     * @Route("/group")
     * @Method({"POST"})
     */
    public function postAction() {

        if ($this->get('security.context')->isGranted('ROLE_LEADER')) {
            $event = new Event();
            return $this->processForm($event);
        } else {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
    }

    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function getAction($id = null) {

        $em = $this->getDoctrine()->getManager();
        $view = View::create();

        if (null != $id) {
//            $event = $em->getRepository('FlashDefaultBundle:UserEvent')->findByCurentUser($id);
//
//            if (null != $event) {
//                $response = $event;
//            } else {
//                $response = array('success' => 'false');
//            }
        } else {

            $events = $em->getRepository('FlashDefaultBundle:Event')->findAll();

            $view->setData($events);
        }

        return $this->handle($view);
    }

    /**
     * @Route("/group/{id}")
     * @Method({"GET"})
     */
    public function getByGroupAction($id = null) {

        $em = $this->getDoctrine()->getManager();
        $view = View::create();
        $acc = $this->get('security.context')->getToken()->getUser();
        if (null != $id) {
//            $event = $em->getRepository('FlashDefaultBundle:UserEvent')->findByCurentUser($id);
//
//            if (null != $event) {
//                $response = $event;
//            } else {
//                $response = array('success' => 'false');
//            }
        } else {
            $events = $em->getRepository('FlashDefaultBundle:Event')->getByGroup($acc->getGroup());
            $view->setData($events);
        }

        return $this->handle($view);
    }

    public function putAction($id) {
        
    }

    private function processForm($event) {

        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new EventType(), $event);

        $form->bind($this->getFromRequest(array('name', 'description', 'city', 'country', 'date')));
        $view = View::create();

        if ($form->isValid()) {

            $acc = $this->get('security.context')->getToken()->getUser();

            $userEvent = $this->get('user_event')->get('add_event', $acc);

            $group = $acc->getGroup();

            $event->setGroup($group);

            $em->persist($event);
            $em->persist($userEvent);
            $em->persist($group);
            $em->flush();
        } else {
            $view->setStatusCode(400);
            return $view->setData($this->getErrorMessages($form));
        }
        return $event;
    }

    public function deleteAction($id) {
        
    }

}
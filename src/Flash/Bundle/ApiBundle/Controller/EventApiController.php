<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Flash\Bundle\DefaultBundle\Entity\Account;
use Flash\Bundle\DefaultBundle\Entity\Event;
use Flash\Bundle\DefaultBundle\Form\EventType;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;
use FOS\RestBundle\View\View;

/**
 * @Route("/admin/rest/api/events")
 */
class EventApiController extends RESTController implements GenericRestApi {

    /**
     * @Route("/country/{country_id}/city/{city_id}")
     * @Method({"GET"})
     * @return single Account data
     */
    public function getEventsByLocation($country_id, $city_id) {
        
    }

    public function deleteAction($id) {
        
    }

    /**
     * @Route("/{id}")
     * @Method({"GET"})
     * @return single Account data
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
     * @Route("")
     * @Method({"POST"})
     * @return single Account data
     */
    public function postAction() {

        $event = new Event();

        return $this->processForm($event);
    }

    public function putAction($id) {
        
    }

    private function processForm($event) {

        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new EventType(), $event);
        $form->bind($this->getFromRequest(array('name', 'description', 'city', 'country', 'date')));
        $user = $this->get('security.context')->getToken()->getUser();
        $view = View::create();

        if ($form->isValid()) {

            $group = $user->getGroup();

            $event->setGroup($group);

            $em->persist($event);
            $em->persist($group);
            $em->flush();
        } else {
            $view->setStatusCode(400);
            return $view->setData($this->getErrorMessages($form));
        }
        return $event;
    }

}
<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Flash\Bundle\DefaultBundle\Entity\Event;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;
use FOS\RestBundle\View\View;

/**
 * @Route("/logged/rest/api/events")
 */
class EventApiController extends RESTController implements GenericRestApi {

    /**
     * @Route("/group")
     * @Method({"POST"})
     */
    public function postAction() {

        if ($this->get('security.context')->isGranted('ROLE_LEADER')) {
            return $this->get('event_service')->processForm(new Event());
        } else {
            return array('error' => 'Access denied');
        }
    }

    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function getAction($id = null) {

        $view = View::create();

        if (null != $id) {
            
        } else {
            $events = $this->getDoctrine()->getManager()->getRepository('FlashDefaultBundle:Event')->findAll();
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
        } else {
            $events = $em->getRepository('FlashDefaultBundle:Event')->getByGroup($acc->getGroup());
            $view->setData($events);
        }

        return $this->handle($view);
    }

    /**
     * @Route("/group/{id}")
     * @Method({"PUT"})
     */
    public function putAction($id) {

        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('FlashDefaultBundle:Event')->find($id);
        if (NULL === $event)
            return array('error' => 'Not found');

        $secContext = $this->get('security.context');

        if (FALSE === $secContext->isGranted('EDIT', $event))
            return array('error' => 'Hey! You don\'t have enought permissions.');

        return $this->get('event_service')->processForm($event);
    }

    public function deleteAction($id) {
    }

}
<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Flash\Bundle\DefaultBundle\Entity\Event;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;
use FOS\RestBundle\View\View;

/**
 * @Route("")
 */
class EventApiController extends RESTController implements GenericRestApi {

    /**
     * @Route("/events")
     * @Method({"GET"})
     */
    public function getCalendarAction() {
        
        
        print_r('{"id":1,"title":"My event","text":"Super text","start":"2013-06-03T21:00:00.000Z","end":"2013-06-03T21:00:00.000Z"}');
        exit();
        
    }

    /**
     * @Route("/events")
     * @Method({"POST"})
     */
    public function postCalendarAction() {


        $view = View::create();
        
        print_r('{"id":2,"title":"My event2","text":"Super text","start":"2013-06-04T21:00:00.000Z","end":"2013-06-03T21:00:00.000Z"}');
        exit();
        return $this->handle($view->setData($resp));
        print_r("Event added");
        
        exit('1');

        $user = $this->get('security.context')->getToken()->getUser();
        $group = $user->getGroup();

        if ($this->get('security.context')->isGranted('EDIT', $group) &&
                $user->getGroup()->getNumberOfParty() >= $user->getGroup()->getMinimumUsersNumber()) {

            return $this->get('event_service')->processForm(new Event());
        } else {
            return array('error' => 'Access denied');
        }
    }

    /**
     * @Route("/logged/rest/api/events/group")
     * @Method({"POST"})
     */
    public function postAction() {

        $user = $this->get('security.context')->getToken()->getUser();
        $group = $user->getGroup();

        if ($this->get('security.context')->isGranted('EDIT', $group) &&
                $user->getGroup()->getNumberOfParty() >= $user->getGroup()->getMinimumUsersNumber()) {

            return $this->get('event_service')->processForm(new Event());
        } else {
            return array('error' => 'Access denied');
        }
    }

    /**
     * @Route("/logged/rest/api/events")
     * @Method({"GET"})
     */
    public function getAction($id = null) {

        $view = View::create();

        if (null != $id) {
            $event = $this->getDoctrine()->getManager()->getRepository('FlashDefaultBundle:Event')->find($id);
            if (NULL != $event) {
                $view->setData($event);
            } else {
                throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
            }
        } else {
            $events = $this->getDoctrine()->getManager()->getRepository('FlashDefaultBundle:Event')->findAll();
            $view->setData($events);
        }
        return $this->handle($view);
    }

    /**
     * @Route("/logged/rest/api/events/group/{id}")
     * @Method({"GET"})
     */
    public function getByGroupAction($id = null) {

        $em = $this->getDoctrine()->getManager();
        $view = View::create();
        $acc = $this->get('security.context')->getToken()->getUser();
        if (NULL != $id) {
            
        } else {
            $events = $em->getRepository('FlashDefaultBundle:Event')->getByGroup($acc->getGroup());
            $view->setData($events);
        }

        return $this->handle($view);
    }

    /**
     * @Route("/logged/rest/api/events/group/{id}")
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
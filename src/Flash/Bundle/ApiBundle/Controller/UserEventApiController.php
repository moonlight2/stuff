<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Flash\Bundle\DefaultBundle\Entity\Account;
use Flash\Bundle\DefaultBundle\Entity\Event;
use FOS\RestBundle\View\View;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;

class UserEventApiController extends RESTController implements GenericRestApi {

    /**
     * @Route("api/logged/account/{acc_id}/events/{from}/{to}",requirements={"from" = "\d+", "to" = "\d+","acc_id" = "\d+"})
     * @Method({"GET"})
     */
    public function getByAccountAction($acc_id, $from = NULL, $to = NULL) {

        $em = $this->getDoctrine()->getManager();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($acc_id);

        if (NULL == $acc) {
            return $this->handle($this->getView(array('error' => 'Not found')));
        }

        if (NULL != $from && NULL != $to) {
            $events = $this->getDoctrine()->getManager()
                            ->getRepository('FlashDefaultBundle:UserEvent')->findAllByUser($acc, $from, $to);
        } else {
            $events = $this->getDoctrine()->getManager()
                            ->getRepository('FlashDefaultBundle:UserEvent')->findAll();
        }
        return $this->handle($this->getView($events));
    }

    /**
     * @Route("api/logged/events/{from}/{to}",requirements={"from" = "\d+", "to" = "\d+"})
     * @Method({"GET"})
     */
    public function getAction($from = null, $to = null) {
        
        $em = $this->getDoctrine()->getManager();

        if (NULL != $from && NULL != $to) {
            $events = $this->getDoctrine()->getManager()
                            ->getRepository('FlashDefaultBundle:UserEvent')->findAllByLimit($from, $to);
        } else {
            $events = $this->getDoctrine()->getManager()
                            ->getRepository('FlashDefaultBundle:UserEvent')->findAll();
        }
        return $this->handle($this->getView($events));
    }

    /**
     * @Route("api/logged/account/{acc_id}/events/{id}",requirements={"from" = "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteAction($id) {

        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('FlashDefaultBundle:UserEvent')->find($id);

        if (NULL != $event) {
            if ($this->get('security.context')->isGranted('DELETE', $event)) {
                $em->persist($event);
                $em->remove($event);
                $em->flush();
                $response = array('success' => 'Event deleted');
            } else {
                $response = array('error' => 'Access denied');
            }
        } else {
            $response = array('error' => 'Not found');
        }
        return $this->handle($this->getView($response));
    }

    public function postAction() {
        
    }

    public function putAction($id) {
        
    }

}
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
    public function getAction($acc_id, $from = NULL, $to = NULL) {

        $em = $this->getDoctrine()->getManager();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($acc_id);
        
        if (NULL == $acc) {
            return $this->handle($this->getView(array('error'=>'Not found')));
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

    public function deleteAction($id) {
        
    }

    public function postAction() {
        
    }

    public function putAction($id) {
        
    }

}
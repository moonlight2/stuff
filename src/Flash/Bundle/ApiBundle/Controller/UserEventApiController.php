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
     * @Route("api/all_events")
     * @Method({"GET"})
     */
    public function getAction($acc_id = null) {

        $events = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:UserEvent')->findAll();

        return $this->handle($this->getView($events));
    }

    public function deleteAction($id) {
        
    }

    public function postAction() {
        
    }

    public function putAction($id) {
        
    }

}
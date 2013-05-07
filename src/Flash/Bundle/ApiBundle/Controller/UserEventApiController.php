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

/**
 * @Route("/logged/rest/api/user_events")
 */
class UserEventApiController extends RESTController implements GenericRestApi {

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
            

            $events = $em->getRepository('FlashDefaultBundle:UserEvent')
                    ->findAllByCurentUser($this->get('security.context')->getToken()->getUser());
            
            $view->setData($events);
        }

        return $this->handle($view);
    }

    public function putAction($id) {
        
    }

    public function deleteAction($id) {
        
    }

    public function postAction() {
        
    }

}
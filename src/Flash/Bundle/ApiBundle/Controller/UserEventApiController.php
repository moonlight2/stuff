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
 * @Route("p{acc_id}/user_events", requirements={"acc_id" = "\d+"})
 */
class UserEventApiController extends RESTController implements GenericRestApi {

    /**
     * @Route("")
     * @Method({"GET"})
     * @return single Account data
     */
    public function getAction($acc_id = null) {

        $em = $this->getDoctrine()->getManager();
        $view = View::create();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($acc_id);

        if (null != $acc_id) {
            $events = $em->getRepository('FlashDefaultBundle:UserEvent')
                    ->findAllByUser($acc);

            if (null != $events) {
                $resp = $events;
            } else {
                $resp = array('success' => 'false');
            }
        } else {
//            $events = $em->getRepository('FlashDefaultBundle:UserEvent')
//                    ->findAllByUser($this->get('security.context')->getToken()->getUser());
        }
        $view->setData($resp);

        return $this->handle($view);
    }

    public function deleteAction($id) {
        
    }

    public function postAction() {
        
    }

    public function putAction($id) {
        
    }

}
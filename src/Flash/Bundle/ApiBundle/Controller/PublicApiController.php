<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\View\View;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;

/**
 * @Route("/api")
 */
class PublicApiController extends RESTController {

    /**
     * @Route("/groups/country/{country_id}/city/{city_id}")
     * @Method({"GET"})
     */
    public function getGroupsByLocationAction($country_id, $city_id) {

        $em = $this->getDoctrine()->getManager();

        $groups = $em->getRepository('FlashDefaultBundle:Group')->getByLocation($country_id, $city_id);
        $view = View::create();
        if (NULL != $groups) {
            $view->setData($groups);
        } else {
            $view->setStatusCode(404);
            $view->setData(array('error' => 'Not found'));
        }

        return $this->handle($view);
    }

}
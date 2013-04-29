<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Flash\Bundle\DefaultBundle\Entity\Account;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;

/**
 * @Route("/rest/api/events")
 */
class EventApiController extends RESTController implements GenericRestApi {

    /**
     * @Route("/country/{country_id}/city/{city_id}")
     * @Method({"POST"})
     * @return single Account data
     */
    public function getEventsByLocation($country_id, $city_id) {
        
    }

    public function deleteAction($id) {
        
    }

    public function getAction($id = null) {
        
    }

    /**
     * @Route("/{type}")
     * @Method({"POST"})
     * @return single Account data
     */
    public function postAction() {
        
    }

    public function putAction($id) {
        
    }

}
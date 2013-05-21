<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\View\View;
use Flash\Bundle\DefaultBundle\Entity\Group;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;

/**
 * @Route("/rest/api/photos")
 */
class PhotoApiController extends RESTController implements GenericRestApi {

    public function deleteAction($id) {
        
    }

    /**
     * @Route("/{id}")
     * @Method({"GET"})
     * @return single Account data
     */
    public function getAction($id = NULL) {
        
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $view = View::create();

        $images = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Photo')->findAll();
        $view->setData($images);
        
        return $this->handle($view);
    }

    public function postAction() {
        
    }

    public function putAction($id) {
        
    }

}
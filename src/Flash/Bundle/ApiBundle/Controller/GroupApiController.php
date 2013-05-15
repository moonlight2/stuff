<?php
namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\View\View;
use Flash\Bundle\DefaultBundle\Entity\Group;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;

/**
 * @Route("/rest/api/groups")
 */
class GroupApiController extends RESTController implements GenericRestApi {

    /**
     * @Route("/{id}")
     * @Method({"GET"})
     * @return single Account data
     */
    public function getAction($id = null) {

        $em = $this->getDoctrine()->getManager();
        $view = View::create();

        if (null != $id) {
        } else {
            $groups = $em->getRepository('FlashDefaultBundle:Group')->findAll();
            $view->setData($groups);
        }

        return $this->handle($view);
    }

    /**
     * @Route("")
     * @Method({"POST"})
     * @return single Account data
     */
    public function postAction() {

        return $this->get('group_service')->processForm(new Group());
    }

    public function putAction($id) {
        
    }

    public function deleteAction($id) {
        
    }

}
<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use Flash\Bundle\DefaultBundle\Entity\Account;
use Flash\Bundle\DefaultBundle\Entity\Group;
use Flash\Bundle\DefaultBundle\Form\GroupType;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;

/**
 * @Route("/rest/api/groups")
 */
class GroupApiController extends RESTController implements GenericRestApi {

    /**
     * @Route("/country/{country_id}/city/{city_id}")
     * @Method({"GET"})
     */
    public function getGroupsByLocationAction($country_id, $city_id) {

        $em = $this->getDoctrine()->getManager();

        $groups = $em->getRepository('FlashDefaultBundle:Group')->getByLocation($country_id, $city_id);
        $view = View::create();
        if (null != $groups) {
            $view->setData($groups);
        } else {
            $view->setStatusCode(404);
            $view->setData(array('success' => 'false'));
        }

        return $this->handle($view);
    }



    public function deleteAction($id) {
        
    }

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

        $group = new Group();

        return $this->processForm($group);
    }

    public function putAction($id) {
        
    }

    private function processForm($group) {

        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new GroupType(), $group);
        $form->bind($this->getFromRequest(array('name', 'city', 'country')));
        $view = View::create();

        if ($form->isValid()) {
            $em->persist($group);
            $em->flush();
        } else {
            $view->setStatusCode(400);
            return $view->setData($this->getErrorMessages($form));
        }
        return $group;
    }

}
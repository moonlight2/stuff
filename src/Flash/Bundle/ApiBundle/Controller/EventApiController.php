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

        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent(), true);



        $role = $em->getRepository('FlashDefaultBundle:Role')->findBy(
                array('name' => 'ROLE_USER'));

        $encoder = $factory->getEncoder($account);
        $password = $encoder->encodePassword($data['password'], $account->getSalt());

        $account->setPassword($password);
        $account->addRole($role[0]);

        $em->persist($role[0]);
        $em->persist($account);

        $em->flush();

        $response = $this->setResponse($account);

        return $this->forward($this->respAction, array(
                    'data' => $response,
                    'type' => $type,
        ));
    }

    public function putAction($id) {
        
    }

}
<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Flash\Bundle\DefaultBundle\Entity\Account;
use Flash\Bundle\DefaultBundle\Entity\Event;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;

/**
 * @Route("/rest/api/events")
 */
class EventApiController extends RESTController implements GenericRestApi {

    /**
     * @Route("/country/{country_id}/city/{city_id}")
     * @Method({"GET"})
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

        $event = new Event();

        return $this->processForm($acc);
    }

    public function putAction($id) {
        
    }

    private function processForm($acc) {

        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new AccountType(), $acc);
        $form->bind($this->getFromRequest(array('username', 'email', 'password', 'about')));
        $view = View::create();

        if ($form->isValid()) {

            if ($request->getMethod() == 'PUT' || true != $em->getRepository('FlashDefaultBundle:Account')
                            ->exists($request->get('username'))) {

                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($acc);
                $password = $encoder->encodePassword($request->get('password'), $acc->getSalt());

                $acc->setUsername($request->get('username'));
                $acc->setPassword($password);
                $acc->setEmail($request->get('email'));
                $acc->setAbout($request->get('about'));
                $acc->setCity($request->get('city'));
                $acc->setCountry($request->get('country'));
                $acc->setDateRegistration(new \DateTime("now"));

                if ($request->getMethod() == 'POST') {

                    $role = $em->getRepository('FlashDefaultBundle:Role')->getByName('ROLE_USER');
                    $acc->addRole($role);
                    $em->persist($role);
                }
                $em->persist($acc);
                $em->flush();
            } else {

                $view->setStatusCode(400);
                return $view->setData(array('success' => 'false'));
            }
        } else {
            $view->setStatusCode(400);
            return $view->setData($this->getErrorMessages($form));
        }
        return $acc;
    }

}
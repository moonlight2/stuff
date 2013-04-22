<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Flash\Bundle\DefaultBundle\Entity\Account;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use FOS\RestBundle\View\View;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\Rest\Util\Codes;
use Flash\Bundle\DefaultBundle\Form\AccountType;

/**
 * @Route("/best")
 */
class BestController extends RESTController {

    /**
     * @Route("/users/{id}", name="_get_account")
     * @Method({"GET"})
     * @param Integer $id
     * @return single Account data or array of accounts
     */
    public function getNewAction($id = null) {

        $em = $this->getDoctrine()->getManager();

        if (null != $id) {
            $account = $em->getRepository('FlashDefaultBundle:Account')->find($id);

            if (null != $account) {
                $response = array('user' => $account);
            } else {
                $response = array('success' => 'false');
            }
        } else {
            $accounts = $em->getRepository('FlashDefaultBundle:Account')->findAll();
            $response = array('users' => $accounts);
        }

        return $response;
    }

    /**
     * @Route("/users/add")
     * @Method({"POST"})
     */
    public function addAction() {

        $acc = new Account();
        return $this->processForm($acc);
    }



    private function processForm($acc) {

        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new AccountType(), $acc);
        $form->bind($this->getFromRequest(array('username', 'email', 'password', 'about')));
        $view = View::create();

        if ($form->isValid()) {

            if (true != $em->getRepository('FlashDefaultBundle:Account')
                            ->exists($request->get('username'))) {

                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($acc);
                $password = $encoder->encodePassword($request->get('password'), $acc->getSalt());

                $acc->setUsername($request->get('username'));
                $acc->setPassword($password);
                $acc->setEmail($request->get('email'));
                $acc->setAbout($request->get('about'));

                $role = $em->getRepository('FlashDefaultBundle:Role')->getByName('ROLE_USER');

                $acc->addRole($role);

                $em->persist($acc);
                $em->persist($role);

                $em->flush();
            } else {
                
                $view->setStatusCode(400);
                return $view->setData(array('success'=>'false'));
            }
        } else {
            $view->setStatusCode(400);
            return $view->setData($this->getErrorMessages($form));
        }
        return $acc;
    }

    /**
     * @Route("/all")
     * @Method({"GET", "POST"})
     */
    public function getAllAction() {

        $em = $this->getDoctrine()->getManager();

        $account = $em->getRepository('FlashDefaultBundle:Account')->find(17);

        $res = array('success' => true);

        return $this->responce($account, '.yml', 201);
    }

}
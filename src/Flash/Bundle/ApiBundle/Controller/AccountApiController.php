<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Flash\Bundle\DefaultBundle\Entity\Account;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;
use Flash\Bundle\DefaultBundle\Form\AccountType;
use FOS\RestBundle\View\View;

/**
 * @Route("/rest/api/accounts")
 */
class AccountApiController extends RESTController implements GenericRestApi {

    /**
     * @Route("/{id}")
     * @Method({"GET"})
     * @param Integer $id
     * @return single Account data or array of accounts
     */
    public function getAction($id = null) {

        $em = $this->getDoctrine()->getManager();

        if (null != $id) {
            $account = $em->getRepository('FlashDefaultBundle:Account')->find($id);

            if (null != $account) {
                $response = $account;
            } else {
                $response = array('success' => 'false');
            }
        } else {
            $response = $em->getRepository('FlashDefaultBundle:Account')->findAll();
        }

        return $response;
    }

    /**
     * @Route("/{id}")
     * @Method({"PUT"})
     * @return single Account data
     */
    public function putAction($id) {

        $em = $this->getDoctrine()->getManager();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($id);

        return $this->processForm($acc);
    }

    /**
     * @Route("")
     * @Method({"POST"})
     * @return single Account data
     */
    public function postAction() {

        $acc = new Account();

        return $this->processForm($acc);
    }

    /**
     * @Route("/{id}")
     * @Method({"DELETE"})
     * @param Integer $id
     */
    public function deleteAction($id) {

        $em = $this->getDoctrine()->getManager();

        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($id);

        $em->persist($acc);
        $em->remove($acc);
        $em->flush();

        return array($acc->getId() => 'deleted');
    }

    /**
     * @Route("/byname/{name}")
     * @Method({"GET"})
     * @param String $name
     * @return single Account data  
     */
    public function getByNameAction($name) {

        $em = $this->getDoctrine()->getManager();

        $account = $em->getRepository('FlashDefaultBundle:Account')->getByName($name);
        if (null != $account) {
            $response = $account;
        } else {
            $view = View::create();
            $view->setStatusCode(404);
            $response = $view->setData(array('success' => 'false'));
        }

        return $response;
    }

    /**
     * @Route("/byemail/{email}")
     * @Method({"GET"})
     * @param String $email
     * @return single Account data
     */
    public function getByEmailAction($email) {

        $em = $this->getDoctrine()->getManager();

        $account = $em->getRepository('FlashDefaultBundle:Account')->getByEmail($email);
        if (null != $account) {
            $response = $account;
        } else {
            $view = View::create();
            $view->setStatusCode(404);
            $response = $view->setData(array('success' => 'false'));
        }

        return $response;
    }

    /**
     * @Route("/byrole/{role}")
     * @Method({"GET"})
     * @param String $role
     * @return array of Accounts
     */
    public function getByRoleAction($role) {

        $em = $this->getDoctrine()->getManager();

        $account = $em->getRepository('FlashDefaultBundle:Account')->getByRole($role);
        if (null != $account) {
            $response = $account;
        } else {
            $view = View::create();
            $view->setStatusCode(404);
            $response = $view->setData(array('success' => 'false'));
        }

        return $response;
    }

    /**
     * This method adds a new role to the account
     * 
     * @Route("/role")
     * @Method({"POST"})
     * @return single Account data
     */
    public function postRoleAction() {

        $em = $this->getDoctrine()->getManager();

        $account = $em->getRepository('FlashDefaultBundle:Account')->find($this->getRequest()->get('account_id'));
        $role = $em->getRepository('FlashDefaultBundle:Role')->find($this->getRequest('role_id'));

        $account->addRole($role);

        $em->persist($account);
        $em->persist($role);

        $em->flush();

        return $account;
    }

    /**
     * This method removes a role from the account
     * 
     * @Route("/role/{accountId}/{roleId}")
     * @Method({"DELETE"})
     * @return single Account data
     */
    public function deleteRoleAction($accountId, $roleId) {

        $em = $this->getDoctrine()->getManager();

        $account = $em->getRepository('FlashDefaultBundle:Account')->find($accountId);
        $role = $em->getRepository('FlashDefaultBundle:Role')->find($roleId);

        $account->removeRole($role);

        $em->persist($account);
        $em->persist($role);

        $em->flush();

        return $account;
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
<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Flash\Bundle\DefaultBundle\Entity\Account;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;
use FOS\RestBundle\View\View;
use JMS\SecurityExtraBundle\Annotation\Secure;

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

        $view = View::create();
        $em = $this->getDoctrine()->getManager();

        if (null != $id) {
            $account = $em->getRepository('FlashDefaultBundle:Account')->find($id);

            if (null != $account) {
                $view->setData($account);
            } else {
                throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
            }
        } else {
            $view->setData($em->getRepository('FlashDefaultBundle:Account')->findAll());
        }

        return $this->handle($view);
    }

    /**
     * @Route("/{id}")
     * @Method({"PUT"})
     * @return single Account data
     * 
     */
    public function putAction($id) {

        $em = $this->getDoctrine()->getManager();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($id);
        if (NULL == $acc)
            return array('error'=>'Not found');

        return $this->processForm($acc);
    }

    /**
     * @Route("")
     * @Method({"POST"})
     * @return single Account data
     */
    public function postAction() {
        
        return $this->get('account_service')->processForm(new Account());
    }

    /**
     * @Route("/{id}")
     * @Method({"DELETE"})
     * @param Integer $id
     * @return array
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
}
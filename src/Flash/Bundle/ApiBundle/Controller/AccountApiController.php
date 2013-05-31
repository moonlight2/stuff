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
 * @Route("")
 */
class AccountApiController extends RESTController implements GenericRestApi {

    /**
     * @Route("/rest/api/accounts/{id}")
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
     * @Route("{acc_id}/accounts/own")
     * @Method({"GET"})
     * @param Integer $id
     * @return single Account data or array of accounts
     */
    public function getOwnAction() {

        $view = View::create();
        $sql = "SELECT 
            id,
            email, 
            first_name,
            last_name,
            city_id, 
            country_id 
        from account WHERE id = :acc_id LIMIT 1";

        $params = array(
            "acc_id" => $this->get('security.context')
                    ->getToken()->getUser()->getId(),
        );

        $stmt = $this->getDoctrine()->getManager()->getConnection()->prepare($sql);
        $stmt->execute($params);
        $arr = $stmt->fetchAll();
        $view->setData($arr[0]);

        return $this->handle($view);
    }
    
    /**
     * @Route("p{acc_id}/accounts/own/update/{id}")
     * @Method({"PUT"})
     * @param Integer $id
     * @return single Account data or array of accounts
     */
    public function updateOwnAction($id) {

        $view = View::create();
        $em = $this->getDoctrine()->getManager();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($id);
        if (NULL == $acc)
            return array('error' => 'Not found');

        return $this->handle($this->get('account_service')->processFormWithoutPassword($acc));
    }

    /**
     * @Route("/rest/api/accounts/{id}")
     * @Method({"PUT"})
     * @return single Account data
     * 
     */
    public function putAction($id) {

        $em = $this->getDoctrine()->getManager();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($id);
        if (NULL == $acc)
            return array('error' => 'Not found');

        return $this->processForm($acc);
    }

    /**
     * @Route("/rest/api/accounts")
     * @Method({"POST"})
     * @return single Account data
     */
    public function postAction() {

        return $this->get('account_service')->processForm(new Account());
    }

    /**
     * @Route("/rest/api/accounts/{id}")
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
     * @Route("/rest/api/accounts/byname/{name}")
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
     * @Route("/rest/api/accounts/byemail/{email}")
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
     * @Route("/rest/api/accounts/byrole/{role}")
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
     * @Route("/rest/api/accounts/role")
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
     * @Route("/rest/api/accounts/role/{accountId}/{roleId}")
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
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
     * @Route("/logged/api/accounts/{id}", requirements={"id" = "\d+"})
     * @Method({"GET"})
     * @param Integer $id
     * @return single Account data or array of accounts
     */
    public function getAction($id = null) {

        $view = View::create();
        $em = $this->getDoctrine()->getManager();

        if (NULL != $id) {
            $account = $em->getRepository('FlashDefaultBundle:Account')->find($id);

            if (NULL != $account) {
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
     * @Route("/api/accounts/leaders")
     * @Method({"GET"})
     * @param Integer $id
     * @return single Account data or array of accounts
     */
    public function getLeadersAction() {

        $view = View::create();
        $em = $this->getDoctrine()->getManager();

        $view->setData($em->getRepository('FlashDefaultBundle:Account')->getLeaders());

        return $this->handle($view);
    }

    /**
     * @Route("/api/accounts/leaders/country/{country_id}/city/{city_id}")
     * @Method({"GET"})
     * @param Integer $id
     * @return single Account data or array of accounts
     */
    public function getLeadersByLocationAction($country_id, $city_id) {

        $view = View::create();
        $em = $this->getDoctrine()->getManager();

        $leaders = $em->getRepository('FlashDefaultBundle:Account')->getLeadersByLocalion($country_id, $city_id);
        if (NULL == $leaders) {
            throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
        }

        return $this->handle($this->getView($leaders));
    }

    /**
     * Method returns extended information aout own account
     * 
     * @Route("/logged/api/accounts/own")
     * @Method({"GET"})
     * @param Integer $id
     * @return single Account data or array of accounts
     */
    public function getOwnAction() {

        $accId = $this->get('security.context')
                        ->getToken()->getUser()->getId();

        $extAccInfo = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Account')->getExtendedInfo($accId);

        return $this->handle($this->getView($extAccInfo));
    }

    /**
     * Method update account data without password
     * 
     * @Route("/logged/api/accounts/{id}", requirements={"id" = "\d+"})
     * @Method({"PUT"})
     * @param Integer $id
     * @return single Account data or array of accounts
     */
    public function putAction($id) {

        $accLogged = $this->get('security.context')->getToken()->getUser();
        $acc = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Account')->find($id);
        if (NULL != $acc) {
            if ($accLogged->equals($acc)) {
                return $this->handle($this->get('account_service')->processFormWithoutPassword($acc));
            } else {
                return $this->handle($this->getView(array('error' => 'Access denied.')));
            }
        }
        return $this->handle($this->getView(array('error' => 'Not found.')));
    }

    /**
     * @Route("/api/accounts")
     * @Method({"POST"})
     * @return single Account data
     */
    public function postAction() {

        return $this->handle($this->get('account_service')->processForm(new Account()));
    }

    /**
     * @Route("/logged/api/accounts/{id}", requirements={"id" = "\d+"})
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
        return $this->handle($this->getView(array('Success' => 'Account deleted.')));
    }

//    /**
//     * @Route("/rest/api/accounts/byname/{name}")
//     * @Method({"GET"})
//     * @param String $name
//     * @return single Account data  
//     */
//    public function getByNameAction($name) {
//
//        $em = $this->getDoctrine()->getManager();
//
//        $account = $em->getRepository('FlashDefaultBundle:Account')->getByName($name);
//        if (null != $account) {
//            $response = $account;
//        } else {
//            $view = View::create();
//            $view->setStatusCode(404);
//            $response = $view->setData(array('success' => 'false'));
//        }
//
//        return $response;
//    }
//
//    /**
//     * @Route("/rest/api/accounts/byemail/{email}")
//     * @Method({"GET"})
//     * @param String $email
//     * @return single Account data
//     */
//    public function getByEmailAction($email) {
//
//        $em = $this->getDoctrine()->getManager();
//
//        $account = $em->getRepository('FlashDefaultBundle:Account')->getByEmail($email);
//        if (null != $account) {
//            $response = $account;
//        } else {
//            $view = View::create();
//            $view->setStatusCode(404);
//            $response = $view->setData(array('success' => 'false'));
//        }
//
//        return $response;
//    }
//
//    /**
//     * @Route("/rest/api/accounts/byrole/{role}")
//     * @Method({"GET"})
//     * @param String $role
//     * @return array of Accounts
//     */
//    public function getByRoleAction($role) {
//
//        $em = $this->getDoctrine()->getManager();
//
//        $account = $em->getRepository('FlashDefaultBundle:Account')->getByRole($role);
//        if (null != $account) {
//            $response = $account;
//        } else {
//            $view = View::create();
//            $view->setStatusCode(404);
//            $response = $view->setData(array('success' => 'false'));
//        }
//
//        return $response;
//    }

    /**
     * This method adds a new role to the account
     * 
     * @Route("/logged/api/accounts/role")
     * @Method({"POST"})
     * @return single Account data
     */
    public function postRoleAction() {

        $em = $this->getDoctrine()->getManager();

        $account = $em->getRepository('FlashDefaultBundle:Account')->find($this->getRequest()->get('account_id'));
        $role = $em->getRepository('FlashDefaultBundle:Role')->find($this->getRequest()->get('role_id'));

        
        //var_dump($this->getRequest()->get('rol_id'));
       // exit();
        
        $account->addRole($role);

        $em->persist($account);
        $em->persist($role);

        $em->flush();

        return $account;
    }

    /**
     * This method removes a role from the account
     * 
     * @Route("/logged/api/accounts/role/{accountId}/{roleId}")
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
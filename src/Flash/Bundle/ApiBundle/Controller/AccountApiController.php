<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Flash\Bundle\DefaultBundle\Entity\Account;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;

/**
 * @Route("/rest/api/accounts")
 */
class AccountApiController extends RESTController implements GenericRestApi {

    private $respAction = 'FlashApiBundle:AccountApi:responseDataType';

    /**
     * @Route("/{id}/{type}")
     * @Method({"PUT"})
     * @return single Account data
     */
    public function putAction(Request $request, $type = null) {

        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $account = $em->getRepository('FlashDefaultBundle:Account')->find($data['id']);

        $account->setUsername($data['username']);
        $account->setEmail($data['email']);

//        $encoder = $factory->getEncoder($account);
//        $password = $encoder->encodePassword($data['password'], $account->getSalt());
//        $account->setPassword($password);

        $em->persist($account);
        $em->flush();

        return $this->forward($this->respAction, array(
                    'data' => $this->setResponse($account),
                    'type' => $type,
        ));
    }

    /**
     * @Route("/{type}")
     * @Method({"POST"})
     * @return single Account data
     */
    public function postAction(Request $request, $type = null) {

        $em = $this->getDoctrine()->getManager();

        $factory = $this->get('security.encoder_factory');
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());

        if (true != $em->getRepository('FlashDefaultBundle:Account')
                        ->exists($data['username'])) {

            $account = new Account($data['email']);
            $account->setUsername($data['username']);
            $account->setAbout($data['about']);

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
        } else {
            $response = array('success' => 'false');
        }
        return $this->forward($this->respAction, array(
                    'data' => $response,
                    'type' => $type,
        ));
    }

    /**
     * @Route("/{id}/{type}")
     * @Method({"DELETE"})
     * @param Integer $id
     */
    public function deleteAction($id, $type = null) {

        $em = $this->getDoctrine()->getManager();

        $account = $em->getRepository('FlashDefaultBundle:Account')->find($id);

        $em->persist($account);
        $em->remove($account);
        $em->flush();
        return $this->forward($this->respAction, array(
                    'data' => array($id => 'deleted'),
                    'type' => $type,
        ));
    }

    /**
     * @Route("/{id}/{type}")
     * @Method({"GET"})
     * @param Integer $id
     * @return single Account data or array of accounts
     */
    public function getAction($id = null, $type = null) {

        $em = $this->getDoctrine()->getManager();

        if (null != $id) {
            $account = $em->getRepository('FlashDefaultBundle:Account')->find($id);

            if (null != $account) {
                $response = $this->setResponse($account);
            } else {
                $response = array('success' => 'false');
            }
        } else {
            $accounts = $em->getRepository('FlashDefaultBundle:Account')->findAll();

            foreach ($accounts as $account) {
                $response[] = $this->setResponse($account);
            }
        }
        return $this->forward($this->respAction, array(
                    'data' => $response,
                    'type' => $type,
        ));
    }

    /**
     * @Route("/byname/{name}/{type}")
     * @Method({"GET"})
     * @param String $name
     * @return single Account data  
     */
    public function getByNameAction($name, $type = null) {

        $em = $this->getDoctrine()->getManager();

        $account = $em->getRepository('FlashDefaultBundle:Account')->getByName($name);
        if (null != $account) {
            $response = $this->setResponse($account);
        } else {
            $response = array('success' => 'false');
        }

        return $this->forward($this->respAction, array(
                    'data' => $response,
                    'type' => $type,
        ));
    }

    /**
     * @Route("/byemail/{email}/{type}")
     * @Method({"GET"})
     * @param String $email
     * @return single Account data
     */
    public function getByEmailAction($email, $type = null) {

        $em = $this->getDoctrine()->getManager();

        $account = $em->getRepository('FlashDefaultBundle:Account')->getByEmail($email);
        if (null != $account) {
            $response = $this->setResponse($account);
        } else {
            $response = array('success' => 'false');
        }
        return $this->forward($this->respAction, array(
                    'data' => $response,
                    'type' => $type,
        ));
    }

    /**
     * @Route("/byrole/{role}/{type}")
     * @Method({"GET"})
     * @param String $role
     * @return array of Accounts
     */
    public function getByRoleAction($role, $type = null) {

        $em = $this->getDoctrine()->getManager();

        $accounts = $em->getRepository('FlashDefaultBundle:Account')->getByRole($role);
        if (null != $accounts) {
            foreach ($accounts as $account) {
                $response[] = $this->setResponse($account);
            }
        } else {
            $response = array('success' => 'false');
        }
        return $this->forward($this->respAction, array(
                    'data' => $response,
                    'type' => $type,
        ));
    }

    /**
     * This method adds a new role to the account
     * 
     * @Route("/role/{type}")
     * @Method({"POST"})
     * @return single Account data
     */
    public function postRoleAction(Request $request, $type = null) {

        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);

        $account = $em->getRepository('FlashDefaultBundle:Account')->find($data['account_id']);
        $role = $em->getRepository('FlashDefaultBundle:Role')->find($data['role_id']);

        $account->addRole($role);

        $em->persist($account);
        $em->persist($role);

        $em->flush();

        return $this->forward($this->respAction, array(
                    'data' => $this->setResponse($account),
                    'type' => $type,
        ));
    }

    /**
     * This method removes a role from the account
     * 
     * @Route("/role/{accountId}/{roleId}/{type}")
     * @Method({"DELETE"})
     * @return single Account data
     */
    public function deleteRoleAction($accountId, $roleId, $type = null) {

        $em = $this->getDoctrine()->getManager();

        $account = $em->getRepository('FlashDefaultBundle:Account')->find($accountId);
        $role = $em->getRepository('FlashDefaultBundle:Role')->find($roleId);

        $account->removeRole($role);

        $em->persist($account);
        $em->persist($role);

        $em->flush();

        return $this->forward($this->respAction, array(
                    'data' => $this->setResponse($account),
                    'type' => $type,
        ));
    }

    /**
     * @param Account $account 
     * @return array
     */
    private function setResponse(Account $account) {

        $response['id'] = $account->getId();
        $response['username'] = $account->getUsername();
        $response['email'] = $account->getEmail();
        $response['is_active'] = $account->getIsActive();
        $response['is_not_expired'] = $account->isAccountNonExpired();
        $response['is_non_locked'] = $account->isAccountNonLocked();
        $response['is_credititials_non_expired'] = $account->isCredentialsNonExpired();
        $response['is_enabled'] = $account->isEnabled();
        $response['roles'] = $account->getRoles();
        $response['about'] = $account->getAbout();

        return $response;
    }

}
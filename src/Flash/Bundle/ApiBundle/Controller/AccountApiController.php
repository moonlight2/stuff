<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Flash\Bundle\DefaultBundle\Entity\Account;

/**
 * @Route("/rest/api")
 */
class AccountApiController extends Controller implements GenericRestApi {

    /**
     * @Route("/accounts/{id}")
     * @Method({"PUT"})
     * @return JSON string: single Account data
     */
    public function putAction(Request $request) {

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

        return new JsonResponse($data);
    }

    /**
     * @Route("/accounts")
     * @Method({"POST"})
     * @return JSON string: single Account data
     */
    public function postAction(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $factory = $this->get('security.encoder_factory');
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());

        if (true != $em->getRepository('FlashDefaultBundle:Account')
                        ->exists($data['username'])) {

            $account = new Account($data['email']);
            $account->setUsername($data['username']);

            $role = $em->getRepository('FlashDefaultBundle:Role')->findBy(
                    array('name' => 'ROLE_ADMIN'));

            $encoder = $factory->getEncoder($account);
            $password = $encoder->encodePassword($data['password'], $account->getSalt());

            $account->setPassword($password);
            $account->addRole($role[0]);

            $em->persist($role[0]);
            $em->persist($account);

            $em->flush();

            return new JsonResponse($this->setResponse($account));
        } else {
            return new JsonResponse(array('success' => 'false'));
        }
    }

    /**
     * @Route("/accounts/{id}")
     * @Method({"DELETE"})
     * @return JSON string  
     */
    public function deleteAction($id) {

        $em = $this->getDoctrine()->getManager();
        $account = $em->getRepository('FlashDefaultBundle:Account')->find($id);

        $em->persist($account);
        $em->remove($account);
        $em->flush();
        return new JsonResponse(array($id => 'deleted'));
    }

    /**
     * @Route("/accounts/{id}")
     * @Method({"GET"})
     * @return JSON string:  single Account data or array of accounts
     */
    public function getAction($id = null) {

        $em = $this->getDoctrine()->getManager();

        if (null != $id) {
            $account = $em->getRepository('FlashDefaultBundle:Account')->find($id);

            if (null != $account) {
                $response = $this->setResponse($account);
            } else {
                return new JsonResponse(array('success' => 'false'));
            }
        } else {
            $accounts = $em->getRepository('FlashDefaultBundle:Account')->findAll();

            $response = array();
            foreach ($accounts as $account) {
                $response[] = $this->setResponse($account);
            }
        }
        return new JsonResponse($response);
    }

    /**
     * @Route("/accounts/byname/{name}")
     * @Method({"GET"})
     * @return JSON string: single Account data  
     */
    public function getByNameAction($name) {

        $em = $this->getDoctrine()->getManager();

        $account = $em->getRepository('FlashDefaultBundle:Account')->getByName($name);
        if (null != $account) {
            $response = $this->setResponse($account);
        } else {
            $response = array('success' => 'false');
        }
        return new JsonResponse($response);
    }

    /**
     * @Route("/accounts/byemail/{email}")
     * @Method({"GET"})
     * @return JSON string: single Account data
     */
    public function getByEmailAction($email) {

        $em = $this->getDoctrine()->getManager();
        $account = $em->getRepository('FlashDefaultBundle:Account')->getByEmail($email);
        if (null != $account) {
            $response = $this->setResponse($account);
        } else {
            $response = array('success' => 'false');
        }
        return new JsonResponse($response);
    }

    /**
     * @Route("/accounts/byrole/{role}")
     * @Method({"GET"})
     * @return JSON string: array of Accounts
     */
    public function getByRoleAction($role) {

        $em = $this->getDoctrine()->getManager();
        $accounts = $em->getRepository('FlashDefaultBundle:Account')->getByRole($role);
        if (null != $accounts) {
            foreach ($accounts as $account) {
                $response[] = $this->setResponse($account);
            }
        } else {
            $response = array('success' => 'false');
        }
        return new JsonResponse($response);
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

        return $response;
    }

}
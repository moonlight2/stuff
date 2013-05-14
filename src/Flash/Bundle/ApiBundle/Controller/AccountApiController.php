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

        $view = View::create();
        $em = $this->getDoctrine()->getManager();

        if (null != $id) {
            $account = $em->getRepository('FlashDefaultBundle:Account')->find($id);

            if (null != $account) {
                $view->setData($account);
            } else {
                throw \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
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
        $form->bind($this->getFromRequest(array('city', 'country', 'firstName', 'lastName', 'email', 'password')));
        $view = View::create();

        if ($form->isValid()) {

            if (true == $em->getRepository('FlashDefaultBundle:Account')
                            ->existsEmail($request->get('email'))) {
                $view->setStatusCode(400);
                return $view->setData(array('email' => array('Такой email уже зарегистрирован')));
            } else {

                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($acc);
                $password = $encoder->encodePassword($request->get('password'), $acc->getSalt());

                $acc->setPassword($password);

                $acc->setDateRegistration(new \DateTime("now"));

                if ($request->getMethod() == 'POST') {

                    $acc->setUsername($request->get('email'));
                    $group = $request->get('group');

                    if (NULL != $group) {
                        $group = $em->getRepository('FlashDefaultBundle:Group')->find($request->get('group'));
                        if (!$group) {
                            throw $this->createNotFoundException('No group found for id ' . $id);
                        }
                        $acc->setGroup($group);
                        $em->persist($group);

                        $cRole = new \Flash\Bundle\DefaultBundle\Entity\CustomRole($acc);
                        $acc->addCustomRole($cRole);
                        $em->persist($cRole);
                    }

                    $userEvent = $userEvent = $this->get('user_event')->get('new_user', $acc);

                    $role = $em->getRepository('FlashDefaultBundle:Role')->getByName('ROLE_LEADER');

                    $acc->addRole($role);
                    $userEvent->setAccount($acc);

                    $em->persist($userEvent);
                    $em->persist($role);
                }

                $em->persist($acc);
                $em->flush();
            }
        } else {
            $view->setStatusCode(400);
            return $view->setData($this->getErrorMessages($form));
        }
        return $acc;
    }

    private function sendEmain($email) {

        $message = \Swift_Message::newInstance()
                ->setSubject('Подтверждение регистрации')
                ->setFrom('yakov.the.smart@gmail.com')
                ->setTo($email)
                ->setBody('Будьте добры, подтвердите регистрацию на сайте');
        $this->get('mailer')->send($message);
    }

}
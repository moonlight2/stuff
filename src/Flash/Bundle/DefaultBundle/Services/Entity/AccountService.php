<?php

namespace Flash\Bundle\DefaultBundle\Services\Entity;

use FOS\RestBundle\View\View;
use Flash\Bundle\DefaultBundle\Form\AccountType;
use Flash\Bundle\DefaultBundle\Services\CommonService;

class AccountService extends CommonService {

    public function processForm($acc) {

        $request = $this->injector->getRequest();
        $em = $this->injector->getDoctrine()->getManager();
        $form = $this->injector->getForm()->create(new AccountType(), $acc);
        $view = View::create();

        $form->bind($this->getFromRequest(array('city', 'country', 'firstName', 'lastName', 'email', 'password')));

        if ($form->isValid()) {

            if (true == $em->getRepository('FlashDefaultBundle:Account')
                            ->existsEmail($request->get('email'))) {
                $view->setStatusCode(400);
                return $view->setData(array('email' => array('Такой email уже зарегистрирован')));
            } else {

                $encoder = $this->injector->getSecurityEncoderFactory()->getEncoder($acc);
                $password = $encoder->encodePassword($request->get('password'), $acc->getSalt());

                $acc->setPassword($password);

                $acc->setDateRegistration(new \DateTime("now"));

                if ($request->getMethod() == 'POST') {

                    $acc->setUsername($request->get('email'));
                    $group = $request->get('group');

                    if (NULL != $group) {
                        $group = $em->getRepository('FlashDefaultBundle:Group')->find($request->get('group'));
                        if (!$group) {
                            return array('error' => 'Group not found.');
                        }
                        $acc->setGroup($group);
                        $em->persist($group);

                        $cRole = new \Flash\Bundle\DefaultBundle\Entity\CustomRole($acc);
                        $acc->addCustomRole($cRole);
                        $em->persist($cRole);
                    }

                    $userEvent = $this->injector->getUserEvent()->get('new_user', $acc);

                    $role = $em->getRepository('FlashDefaultBundle:Role')->getByName('ROLE_USER');

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
        return $view->setData($acc);
    }

    public function processFormWithoutPassword($acc) {

        $request = $this->injector->getRequest();
        $em = $this->injector->getDoctrine()->getManager();
        $form = $this->injector->getForm()->create(new AccountType(), $acc);
        $view = View::create();
        $ownAcc = $this->context->getToken()->getUser();
        
        $form->bind(array(
            'city' => $request->get('city'),
            'country' => $request->get('country'),
            'firstName' => $request->get('firstName'),
            'lastName' => $request->get('lastName'),
            'email' => $request->get('email'),
            'password' => $ownAcc->getPassword(),
        ));

        if ($form->isValid()) {

                $ownAcc->setCity($acc->getCity());
                $ownAcc->setCountry($acc->getCountry());
                $ownAcc->setFirstName($acc->getFirstName());
                $ownAcc->setLastName($acc->getLastName());
                $ownAcc->setEmail($acc->getEmail());
                $ownAcc->setUsername($acc->getEmail());

                $em->persist($ownAcc);
                $em->flush();

        } else {
            $view->setStatusCode(400);
            return $view->setData($this->getErrorMessages($form));
        }
        return $view->setData($ownAcc);
    }
    
    public function changePassword($pass1, $pass2) {

        $request = $this->injector->getRequest();
        $em = $this->injector->getDoctrine()->getManager();
        $form = $this->injector->getForm()->create(new AccountType(), $acc);
        $view = View::create();
        $ownAcc = $this->context->getToken()->getUser();
        
        $form->bind(array(
            'city' => $request->get('city'),
            'country' => $request->get('country'),
            'firstName' => $request->get('firstName'),
            'lastName' => $request->get('lastName'),
            'email' => $request->get('email'),
            'password' => $ownAcc->getPassword(),
        ));

        if ($form->isValid()) {

                $ownAcc->setCity($acc->getCity());
                $ownAcc->setCountry($acc->getCountry());
                $ownAcc->setFirstName($acc->getFirstName());
                $ownAcc->setLastName($acc->getLastName());
                $ownAcc->setEmail($acc->getEmail());
                $ownAcc->setUsername($acc->getEmail());

                $em->persist($ownAcc);
                $em->flush();

        } else {
            $view->setStatusCode(400);
            return $view->setData($this->getErrorMessages($form));
        }
        return $view->setData($ownAcc);
    }

}

?>
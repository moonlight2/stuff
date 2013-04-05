<?php

namespace Flash\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Flash\Bundle\DefaultBundle\Entity\Account;

class DefaultController extends Controller {

    /**
     * @Route("/flash/{name}")
     * @Template()
     */
    public function indexAction($name) {

        $em = $this->getDoctrine()->getManager();

        $factory = $this->get('security.encoder_factory');

        if (true != $em->getRepository('FlashDefaultBundle:Account')
                        ->exists($name)) {
            
            $account = new Account("email@home.com");
            $account->setUsername($name);
            
            $role = $em->getRepository('FlashDefaultBundle:Role')->findBy(
                    array('name' => 'ROLE_ADMIN'));

            $encoder = $factory->getEncoder($account);
            $password = $encoder->encodePassword('pass', $account->getSalt());

            $account->setPassword($password);
            $account->addRole($role[0]);

            $em->persist($role[0]);
            $em->persist($account);

            $em->flush();

            return array('name' => $name);
        } else {
            return array('name' => 'Error! User is already exists!');
        }
    }

    /**
     * @Route("/get/{name}")
     * @Template()
     */
    public function getAction($name = null) {

        $em = $this->getDoctrine()->getManager();

        $account = $em->getRepository('FlashDefaultBundle:Account')->get($name);

        print_r($account->getRoles());

        exit();
    }

}

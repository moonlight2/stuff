<?php

namespace Flash\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Flash\Bundle\DefaultBundle\Entity\Account;

class DefaultController extends Controller {

    /**
     * @Route("/rest/{id}", name="_default_rest")
     * @Template()
     */
    public function restAction($id = null) {

        return array('name' => 'Supername');
    }


    /**
     * @Route("/wines/{id}")
     */
    public function wineAction($id = null) {

        $em = $this->getDoctrine()->getManager();

        if (null != $id) {
            $wine = $em->getRepository('FlashDefaultBundle:Wine')->find($id);

            $resp['id'] = $wine->getId();
            $resp['name'] = $wine->getName();
            $resp['grapes'] = $wine->getGrapes();
            $resp['country'] = $wine->getCountry();
            $resp['region'] = $wine->getRegion();
            $resp['year'] = $wine->getYear();
            $resp['description'] = $wine->getDescription();
            $resp['picture'] = $wine->getPicture();

            return new JsonResponse($resp);
        } else {

            $wines = $em->getRepository('FlashDefaultBundle:Wine')->findAll();

            $resp = array();
            $end = array();
            foreach ($wines as $wine) {
                $resp['id'] = $wine->getId();
                $resp['name'] = $wine->getName();
                $resp['grapes'] = $wine->getGrapes();
                $resp['country'] = $wine->getCountry();
                $resp['region'] = $wine->getRegion();
                $resp['year'] = $wine->getYear();
                $resp['description'] = $wine->getDescription();
                $resp['picture'] = $wine->getPicture();
                $end[] = $resp;
            }

            return new JsonResponse($end);
        }
    }

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

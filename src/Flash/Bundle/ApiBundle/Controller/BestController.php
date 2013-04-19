<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Flash\Bundle\DefaultBundle\Entity\Account;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use FOS\RestBundle\View\View;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\Rest\Util\Codes;

/**
 * @Route("/best")
 */
class BestController extends RESTController implements ClassResourceInterface {

    /**
     * @Route("/get")
     * @Method({"GET", "POST"})
     * @Rest\View()
     */
    public function getAction() {

        $em = $this->getDoctrine()->getManager();

        $account = $em->getRepository('FlashDefaultBundle:Account')->find(17);
        $group = $em->getRepository('FlashDefaultBundle:Group')->find(1);

//        $acc = new Account('email2');
//        $acc->setUsername('boris2');
//        $acc->setPassword('pass');
//        $acc->setAbout('sss');
//        $acc->setGroup($group);
//        $em->persist($group);
//        $em->persist($acc);
//        $em->flush();
//
//        $serializer = $this->container->get('serializer');
//        $data = $serializer->serialize($acc, 'xml');
        return array('account' => $account);

//        return View::create()->setData($name)
//                ->setTemplate(new TemplateReference('AcmeTestBundle', 'Default', 'index'))
//                ->setStatusCode('202')
//                ->setHeader('Content-Type', 'text/html  ');
    }

    /**
     * @Route("/all")
     * @Method({"GET", "POST"})
     */
    public function getAllAction() {
        
        $em = $this->getDoctrine()->getManager();
        
        $account = $em->getRepository('FlashDefaultBundle:Account')->find(17);
        
        $res = array('success'=>true);
        
        return $this->responce($account, '.yml', 201);
    }

}
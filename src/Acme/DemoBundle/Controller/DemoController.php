<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Acme\DemoBundle\Form\ContactType;
use Acme\DemoBundle\Entity\Account;
// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DemoController extends Controller {

    public function __construct() {
        
    }

    /**
     * @Route("/", name="_demo")
     * @Template()
     */
    public function indexAction() {
        return array();
    }

    /**
     * @Route("/json", name="_demo_json")
     */
    public function jsonAction() {
        $text = 'This is content from Json Action';
        $body = 'And this is from Route Action forward';
        return new JsonResponse(array('name' => $text, 'body' => $body));
    }

    
    
    /**
     * @Route("/json2", name="_demo_json2")
     */
    public function json2Action() {

        $text = 'Здесь будет другой текст с другой страницы';
        $body = 'Это будет текст тела, но уже с другой страницы';

        $content = (!isset($_SERVER['HTTP_X_PJAX'])) ?
                ($this->renderView(
                        'AcmeDemoBundle:Demo:json3.html.twig', array('name' => $text, 'body' => $body))) :
                ($this->renderView(
                        'AcmeDemoBundle:Demo:json2.html.twig', array('name' => $text, 'body' => $body)));
        
        return new Response($content);
    }

    /**
     * @Route("/route", name="_demo_route")
     */
    public function routeAction() {
        if (!isset($_SERVER['HTTP_X_PJAX'])) {
            return $this->forward('AcmeDemoBundle:Demo:fromjson');
        } else {
            return $this->forward('AcmeDemoBundle:Demo:json');
        }
    }

    /**
     * @Route("/encode", name="_demo_encode")
     * @Template()
     */
    public function fromjsonAction() {
        return array();
    }

    /**
     * @Route("/email/{id}", name="_demo_email")
     * @Template()
     */
    public function emailAction($id = null) {

//        $ice = $this->get('ice');

        $iceManager = $this->get('ice_manager');

        if (null != $id) {
            $message = \Swift_Message::newInstance()
                    ->setSubject('Pisik misikk')
                    ->setFrom('yakov.the.smart@gmail.com')
                    ->setTo('yakov.the.smart@gmail.com')
                    ->setBody($id);
            $this->get('mailer')->send($message);
            return array('name' => 'Email sent!');
        } else {
            return array('name' => $iceManager->get()->getIce());
        }
    }

    /**
     * @Route("/query/{name}", name="_demo_query")
     * @Template()
     */
    public function queryAction($name) {

        $em = $this->getDoctrine()->getManager();
        $reposiyory = $em->getRepository('AcmeDemoBundle:Account');
        $acc = $reposiyory->loadUserByUserName($name);

        return array('name' => $acc[0]->getProduct()->getName());
    }

    /**
     * @Route("/test/{id}", name="_demo_test")
     * @Template()
     */
    public function testAction($id) {

        $repository = $this->getDoctrine()
                ->getRepository('AcmeDemoBundle:Account');

        $acc = $repository->find($id);
        $acc = $repository->findOneByUsername('ilisa');
//        $all = $repository->findAll();
        $acc = $repository->findOneBy(array('username' => 'moo',
            'password' => 'Password'));

        $all = $repository->findBy(array('username' => 'moo'), array('password' => 'ASC'));


//        foreach ($all as $o) {
//            echo $o->getUsername() . "<br>";
//            echo $o->getPassword() . "<br>";
//        }
//        exit('2');

        if (!$acc) {
            echo "Not found";
            exit();
        }

        print_r($acc->getUsername() . "  " . $acc->getPassword());

        exit('1');
    }

    /**
     * @Route("/hello/{name}/{pass}", name="_demo_hello")
     * @Template()
     */
    public function helloAction($name, $pass = null) {

        $factory = $this->get('security.encoder_factory');

        $account = new Account("email@home.com");
        $category = new \Acme\DemoBundle\Entity\Category();
        $category->setName('MUSSIK');

        $encoder = $factory->getEncoder($account);
        $password = $encoder->encodePassword($pass, $account->getSalt());

        $account->setUsername($name);
        $account->setPassword($password);
        $account->setProduct($category);



        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->persist($account);

        $em->flush();

        return array('name' => $name);
    }

    /**
     * @Route("/contact", name="_demo_contact")
     * @Template()
     */
    public function contactAction() {
        $form = $this->get('form.factory')->create(new ContactType());

        $request = $this->get('request');
        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $mailer = $this->get('mailer');
                // .. setup a message and send it
                // http://symfony.com/doc/current/cookbook/email.html

                $this->get('session')->setFlash('notice', 'Message sent!');

                return new RedirectResponse($this->generateUrl('_demo'));
            }
        }

        return array('form' => $form->createView());
    }

}

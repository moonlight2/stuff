<?php

namespace Acme\Bundle\TestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Flash\Bundle\DefaultBundle\Entity\Account;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\View\View,
    FOS\RestBundle\View\ViewHandler,
    FOS\RestBundle\View\RouteRedirectView;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference,
    Symfony\Component\Routing\Exception\ResourceNotFoundException,
    Symfony\Component\Validator\ValidatorInterface;

class TestdController extends FOSRestController {

    public function indexAction($name) {
        echo $name;
        exit();
    }

    /**
     * @Rest\View
     */
    public function allAction() {

        $account = new Account('mail');
        $account->setUsername('name');

        $data = array('name' => array('musers' => 'users'));
        $view = $this->view($data, 404)
        ->setHeader('Content-Type', 'application/json')
                ->setTemplate(new TemplateReference('AcmeTestBundle', 'Default', 'index'))
//                ->setTemplateVar('root');
        ->setData($account);

//        $user = false;
//        if (!$user) {
//            throw new NotFoundHttpException('User not found');
//        }

        return array('this'=>'ilis');
    }

    public function serializedAction() {

        $account = new Account('mail');
        $account->setUsername('name');

        $view = new View();
        $view->setData($account);

        $context = new SerializationContext();
        $context->setVersion('2.1');
        $context->setGroups(array('data'));
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

}

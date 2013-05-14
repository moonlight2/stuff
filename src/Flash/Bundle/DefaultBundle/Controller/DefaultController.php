<?php

namespace Flash\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller {

    /**
     * @Route("/main", name="main_page")
     * @Template()
     */
    public function mainAction() {

        $user = $this->get('security.context')->getToken()->getUser();

        return array(
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName()
        );
    }

    /**
     * @Route("/id{id}", name="user_page")
     * @Template()
     */
    public function userAction($id = null) {

        
        $em = $this->getDoctrine()->getManager();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($id);
            
        if (null != $acc) {
//            exit($id);
            return array(
                'firstName' => $acc->getFirstName(),
                'lastName' => $acc->getLastName()
            );
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Page not found');
        }
    }

}

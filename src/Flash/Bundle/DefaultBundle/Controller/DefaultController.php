<?php

namespace Flash\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Flash\Bundle\DefaultBundle\Entity\Event;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class DefaultController extends Controller {

    /**
     * @Route("/upload", name="upload_page")
     * @Template()
     */
    public function uploadAction() {

        return array('name'=>'Upload action');
        
    }
    
    
    /**
     * @Route("/gallery", name="gallery_page")
     * @Template()
     */
    public function galleryAction() {

        return array('name'=>'My gallery');
        
    }
    
    /**
     * @Route("/gallery2", name="gallery2_page")
     * @Template()
     */
    public function gallery2Action() {

        return array('name'=>'My gallery2');
        
    }

    /**
     * @Route("/test", name="test_page")
     * @Template()
     */
    public function testAction() {

        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();
        $group = $this->getDoctrine()->getManager()->getRepository('FlashDefaultBundle:Group')->find(3);

        var_dump($group->getNumberOfParty());
//        var_dump($this->get('security.context')->isGranted('EDIT', $event));
        echo "Test";
        exit();
    }

    /**
     * @Route("/main", name="main_page")
     * @Template()
     */
    public function mainAction() {

//        $request = $this->get('event_service');
//        $request->getRequest();
//        exit();

        $user = $this->get('security.context')->getToken()->getUser();

//        var_dump($user->getGroup());
//        exit();

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

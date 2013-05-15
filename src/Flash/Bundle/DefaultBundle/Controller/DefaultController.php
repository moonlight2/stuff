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
     * @Route("/test", name="test_page")
     * @Template()
     */
    public function testAction() {

        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
//        $acl = $this->container->get('acl_service');

//            $event = new Event();
//            $event->setName('New Event6');
//            $event->setCity(2);
//            $event->setCountry(45);
//            $event->setDate(new \DateTime('2012-12-12 00:00'));
//            $em->persist($event);
//            $em->flush();
//
//
//            // creating the ACL
            $aclProvider = $this->get('security.acl.provider');
            
            print_r($aclProvider);
//            $objectIdentity = ObjectIdentity::fromDomainObject($event);
//            $acl = $aclProvider->createAcl($objectIdentity);
//
//            // retrieving the security identity of the currently logged-in user
//            $securityContext = $this->get('security.context');
//            $user = $securityContext->getToken()->getUser();
//            $securityIdentity = UserSecurityIdentity::fromAccount($user);
//
//            // grant owner access
//            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
//            $aclProvider->updateAcl($acl);



//        $event = $this->getDoctrine()->getManager()->getRepository('FlashDefaultBundle:Event')->find(25);
//        print_r($event);
//        var_dump($this->get('security.context')->isGranted('EDIT', $event));
        
        
        
//        $acl->removeAuthorFromEntity($event);
//        
//        if (false == $this->get('security.context')->isGranted('VIEW', $event)) {
//            throw new AccessDeniedException();
//        }
//        $acl->setAuthorForEntity($event);
        echo "Test";
        exit();
//        $request = $this->get('event_service');
//        $request->getRequest();
//        exit();
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

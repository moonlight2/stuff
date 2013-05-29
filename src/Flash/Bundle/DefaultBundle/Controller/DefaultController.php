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

        return array('name' => 'Upload action');
    }

    /**
     * @Route("/p{acc_id}/gallery", requirements={"id" = "\d+"}, name="_gallery")
     * @Template()
     */
    public function galleryAction($acc_id = null) {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        if (NULL != $acc_id) {
            $acc = $em->getRepository('FlashDefaultBundle:Account')->find($acc_id);
            if (NULL != $acc) {
                return array('name' => 'My gallery2' . $acc->getEmail(),
                    'acc_id' => $acc->getId(),
                    'own_id' => $user->getId());
            } else {
                throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
            }
        } else {
            throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
        }
    }

    /**
     * @Route("/test", name="test_page")
     * @Template()
     */
    public function testAction() {


        $imgR = $this
                ->get('liip_imagine.controller');
          // filter defined in config.yml

        // string to put directly in the "src" of the tag <img>
       // $cacheManager = $this->get('liip_imagine.cache.manager');
      //  $srcPath = $cacheManager->getBrowserPath('/image/14/f847d9d2c15e618314cd4551206550197cde3126.jpeg', 'my_thumb');
        print_r($imgR);

        //return array('name' => 'My name');
        //echo "Test";
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

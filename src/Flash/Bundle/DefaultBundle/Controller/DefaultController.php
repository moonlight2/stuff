<?php

namespace Flash\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
    }

    /**
     * @Route("/main", name="main_page")
     * @Template()
     */
    public function mainAction() {

        $acc = $this->get('security.context')->getToken()->getUser();
        return $this->redirect($this->generateUrl('_user_page', array('id'=>$acc->getId())), 301);
    }

    /**
     * @Route("/p{id}", name="_user_page")
     * @Template()
     */
    public function userAction($id = null) {

        $em = $this->getDoctrine()->getManager();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($id);

        if (null != $acc) {
            return array(
                'firstName' => $acc->getFirstName(),
                'lastName' => $acc->getLastName(),
                'acc_id' => $acc->getId(),
            );
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Page not found');
        }
    }

}

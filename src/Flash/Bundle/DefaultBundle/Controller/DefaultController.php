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
     * @Route("/test/{name}", name="test_page")
     * @Template()
     */
    public function testAction($name = null) {

        $message = \Swift_Message::newInstance()
                ->setSubject('Hello Email')
                ->setFrom('yakov.the.smart@gmail.com')
                ->setTo('yakov.the.smart@gmail.com')
                ->setBody('Latter'
                )
        ;
        $this->get('mailer')->send($message);
        exit('1');
    }

    function validateDate($date) {
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z$/', $date, $parts) == true) {
            $time = gmmktime($parts[4], $parts[5], $parts[6], $parts[2], $parts[3], $parts[1]);

            $input_time = strtotime($date);
            if ($input_time === false)
                return false;

            return $input_time == $time;
        } else {
            return false;
        }
    }

    /**
     * @Route("/main", name="main_page")
     * @Template()
     */
    public function mainAction() {

        $acc = $this->get('security.context')->getToken()->getUser();
        return $this->redirect($this->generateUrl('_user_page', array('id' => $acc->getId())), 301);
    }

    /**
     * @Route("/p{id}", name="_user_page")
     * @Template()
     */
    public function userAction($id = null) {

        $em = $this->getDoctrine()->getManager();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($id);
        $loggedAcc = $this->get('security.context')->getToken()->getUser();

        $is_leader = ($loggedAcc->getIsLeader()) ? 1 : 0;

        if (null != $acc) {
            return array(
                'firstName' => $acc->getFirstName(),
                'lastName' => $acc->getLastName(),
                'acc_id' => $acc->getId(),
                'own_id' => $loggedAcc->getId(),
                'is_leader' => $is_leader,
            );
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Page not found');
        }
    }

    /**
     * @Route("/feed", name="_feed_page")
     * @Template()
     */
    public function feedAction() {

        $acc = $this->get('security.context')->getToken()->getUser();
        return array(
            'firstName' => $acc->getFirstName(),
            'lastName' => $acc->getLastName(),
            'own_id' => $acc->getId(),
        );
    }

    /**
     * @Route("/feed2", name="_feed2_page")
     * @Template()
     */
    public function feed2Action() {

        $acc = $this->get('security.context')->getToken()->getUser();
        return array(
            'firstName' => $acc->getFirstName(),
            'lastName' => $acc->getLastName(),
            'own_id' => $acc->getId(),
        );
    }

    /**
     * @Route("/p{id}/profile",  requirements={"id" = "\d+"}, name="_userp_profile__page")
     * @Template()
     */
    public function userProfileAction($id = null) {

        $em = $this->getDoctrine()->getManager();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($id);
        $user = $this->get('security.context')->getToken()->getUser();

        if ($user->getId() != $id) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException('Access denied');
        }

        if (null != $acc) {
            return array(
                'firstName' => $acc->getFirstName(),
                'lastName' => $acc->getLastName(),
                'acc_id' => $acc->getId(),
                'own_id' => $user->getId()
            );
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Page not found');
        }
    }

}

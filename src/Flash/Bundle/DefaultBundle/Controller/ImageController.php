<?php

namespace Flash\Bundle\DefaultBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/image")
 */
class ImageController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller {

    /**
     * @Route("/{acc_id}/{img_name}", requirements={"acc_id" = "\d+"})
     * @Method({"GET"})
     */
    public function getAction($acc_id, $img_name = null) {

        $acc = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Account')->find($acc_id);

        $image = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Photo')->getByAccountAndPath($acc, $img_name);

        if (NULL == $image) {
            throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
        }

        header('Content-Type: image/jpeg');
        readfile($image->getAbsolutePath());
        exit();
    }

    /**
     * @Route("/thumb/{acc_id}/{img_name}")
     * @Method({"GET"})
     */
    public function getThumbnailAction($acc_id, $img_name = null) {

        $acc = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Account')->find($acc_id);

        $image = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Photo')->getByAccountAndPath($acc, $img_name);

        if (NULL == $image) {
            throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
        }

        header('Content-Type: image/jpeg');
        readfile($image->getAbsoluteThumbnailPath());
        exit();
    }
    
    /**
     * @Route("/avatar/{acc_id}")
     * @Method({"GET"})
     */
    public function getAvatarAction($acc_id) {

        $acc = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Account')->find($acc_id);
        

        $image = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Photo')->getAvatarByAccount($acc);

        if (NULL == $image) {
            throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
        }

        header('Content-Type: image/jpeg');
        readfile($image->getAbsoluteAvatarPath());
        exit();
    }

}
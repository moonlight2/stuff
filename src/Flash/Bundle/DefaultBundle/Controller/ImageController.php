<?php

namespace Flash\Bundle\DefaultBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/image")
 */
class ImageController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller {

    /**
     * @Route("/{acc_id}/{img_name}")
     * @Method({"GET"})
     */
    public function getByAccountAndPath($acc_id, $img_name = null) {

        $acc = $image = $this->getDoctrine()->getManager()
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

}
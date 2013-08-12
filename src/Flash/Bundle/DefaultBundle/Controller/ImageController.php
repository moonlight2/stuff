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

        $si = new \Flash\Bundle\DefaultBundle\Lib\SimpleImage();
        $img = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Photo')->getByPath($img_name);

        if (NULL == $img) {
            throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
        }
        $si->load($img->getAbsolutePath());
        $si->output();
        exit();
    }

    /**
     * @Route("/thumb/h{h}/{acc_id}/{img_name}", requirements={"acc_id" = "\d+", "h" = "\d+"})
     * @Method({"GET"})
     */
    public function resizeHeigthAction($acc_id, $h, $img_name = null) {

        $si = new \Flash\Bundle\DefaultBundle\Lib\SimpleImage();
        $img = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Photo')->getByPath($img_name);

        if (NULL == $img) {
            throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
        }
        $si->load($img->getAbsolutePath());
        if ($si->getHeight() > $h) {
            $si->resizeToHeight($h);
        }
        $si->output();
        exit();
    }

    /**
     * @Route("/thumb/h{h}/crop/w{c_w}/h{c_h}/{acc_id}/{img_name}", requirements={"acc_id" = "\d+", "h" = "\d+", "c_h" = "\d+", "c_w" = "\d+"})
     * @Method({"GET"})
     */
    public function resizeHeigthAndCropAction($acc_id, $h, $c_w, $c_h, $img_name = null) {

        $si = new \Flash\Bundle\DefaultBundle\Lib\SimpleImage();
        $img = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Photo')->getByPath($img_name);

        if (NULL == $img) {
            throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
        }
        $si->load($img->getAbsolutePath());
        if ($si->getHeight() > $h) {
            $si->resizeToHeight($h);
        }
        $si->crop($c_w, $c_h);

        $si->output();
        exit();
    }
    
    /**
     * @Route("/thumb/crop/w{c_w}/h{c_h}/{acc_id}/{img_name}", requirements={"acc_id" = "\d+", "h" = "\d+", "c_h" = "\d+", "c_w" = "\d+"})
     * @Method({"GET"})
     */
    public function smartCropAction($acc_id, $c_w, $c_h, $img_name = null) {

        $si = new \Flash\Bundle\DefaultBundle\Lib\SimpleImage();
        $img = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Photo')->getByPath($img_name);

        if (NULL == $img) {
            throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
        }
        $si->load($img->getAbsolutePath());
        $si->smartCrop($c_w, $c_h);

        $si->output();
        exit();
    }

        /**
     * @Route("/thumb/crop/{crop}/{acc_id}/{img_name}", requirements={"acc_id" = "\d+", "crop" = "\d+"})
     * @Method({"GET"})
     */
    public function cubeCropAction($acc_id, $crop, $img_name = null) {

        $si = new \Flash\Bundle\DefaultBundle\Lib\SimpleImage();
        $img = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Photo')->getByPath($img_name);

        if (NULL == $img) {
            throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
        }
        $si->load($img->getAbsolutePath());
        $si->cubeCrop($crop);

        $si->output();
        exit();
    }
    
    
    /**
     * @Route("/thumb/w{w}/{acc_id}/{img_name}", requirements={"acc_id" = "\d+", "w" = "\d+"})
     * @Method({"GET"})
     */
    public function resizeWidthAction($acc_id, $w, $img_name = null) {

        $si = new \Flash\Bundle\DefaultBundle\Lib\SimpleImage();
        $img = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Photo')->getByPath($img_name);

        if (NULL == $img) {
            throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
        }
        $si->load($img->getAbsolutePath());
        if ($si->getWidth() > $w) {
            $si->resizeToWidth($w);
        }
        $si->output();
        exit();
    }

    /**
     * @Route("/thumb/w{w}/crop/w{c_w}/h{c_h}/{acc_id}/{img_name}", requirements={"acc_id" = "\d+", "w" = "\d+", "c_h" = "\d+", "c_w" = "\d+"})
     * @Method({"GET"})
     */
    public function resizeWidthAndCropAction($acc_id, $w, $c_w, $c_h, $img_name = null) {

        $si = new \Flash\Bundle\DefaultBundle\Lib\SimpleImage();
        $img = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Photo')->getByPath($img_name);

        if (NULL == $img) {
            throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
        }
        $si->load($img->getAbsolutePath());
        if ($si->getWidth() > $w) {
            $si->resizeToWidth($w);
        }
        $si->crop($c_w, $c_h);

        $si->output();
        exit();
    }

    /**
     * @Route("/fi/{acc_id}/{img_name}", requirements={"acc_id" = "\d+"})
     * @Method({"GET"})
     */
    public function getFeedImageAction($acc_id, $img_name = null) {

        $acc = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Account')->find($acc_id);

        $image = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Photo')->getByAccountAndPath($acc, $img_name);

        if (NULL == $image) {
            throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
        }

        header('Content-Type: image/jpeg');
        if ($image->isGarbage()) {
            readfile($image->getAbsoluteAlbumPath('garbage'));
        } else {
            readfile($image->getAbsolutePath());
        }
        exit();
    }

    /**
     * @Route("/thumb/{acc_id}/{img_name}")
     * @Method({"GET"})
     */
    public function getThumbnailAction($acc_id, $img_name = null) {

        $si = new \Flash\Bundle\DefaultBundle\Lib\SimpleImage();
        $img = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Photo')->getByPath($img_name);

        if (NULL == $img) {
            throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
        }
        $si->load($img->getAbsoluteAlbumPath('thumb'));
        $si->output();
        exit();
    }
    
    /**
     * @Route("/thumb64/{acc_id}/{img_name}")
     * @Method({"GET"})
     */
    public function getThumbnail64Action($acc_id, $img_name = null) {

        $si = new \Flash\Bundle\DefaultBundle\Lib\SimpleImage();
        $img = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Photo')->getByPath($img_name);

        if (NULL == $img) {
            throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
        }

        $path = $img->getAbsoluteAlbumPath('thumb');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        echo $base64;
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
        readfile($image->getAbsoluteAlbumPath('avatar'));
        exit();
    }

}
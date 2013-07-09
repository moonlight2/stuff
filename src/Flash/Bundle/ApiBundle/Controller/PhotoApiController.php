<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\View\View;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;
use Flash\Bundle\DefaultBundle\Entity\Photo;

/**
 * @Route("")
 */
class PhotoApiController extends RESTController implements GenericRestApi {

    /**
     * @Route("logged/api/account/{acc_id}/album/{alb_id}/photos/{id}", requirements={"id" = "\d+", "acc_id" = "\d+", "alb_id" = "\d+"})
     * @Method({"GET"})
     */
    public function getAction($acc_id, $id = NULL, $alb_id = null) {

        $em = $this->getDoctrine()->getManager();
        $view = View::create();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($acc_id);
        $alb = $em->getRepository('FlashDefaultBundle:Album')->find($alb_id);

        if (NULL === $acc || NULL === $alb) {
            return $this->handle($view->setData(array('error' => 'Not found')));
        }

        if (NULL != $id) {
            $photo = $em->getRepository('FlashDefaultBundle:Photo')->findByAccountAndId($acc, $id);
            if (NULL != $photo) {
                $view->setData($photo);
            } else {
                $view->setData(array('error' => 'Not found'));
            }
        } else {
            $photos = $em->getRepository('FlashDefaultBundle:Photo')->findAllByAccountAndAlbum($acc, $alb);
            $view->setData($photos);
        }
        return $this->handle($view);
    }

    /**
     * @Route("logged/api/account/{acc_id}/album/{alb_id}/photos", requirements={"acc_id" = "\d+", "alb_id" = "\d+"})
     * @Route("")
     * @Method({"POST"})
     */
    public function postAction($alb_id = null) {

        $em = $this->getDoctrine()->getManager();
        $alb = $em->getRepository('FlashDefaultBundle:Album')->find($alb_id);

        if (NULL === $alb) {
            return $this->handle($view->setData(array('error' => 'Not found')));
        }
        return $this->handle($this->get('photo_service')->processForm(new Photo(), $alb_id));
    }

    public function putAction($id) {
        
    }

    /**
     *  @Route("logged/api/account/{acc_id}/album/{alb_id}/photos/{id}", requirements={"id" = "\d+", "acc_id" = "\d+", "alb_id" = "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteAction($acc_id, $id = NULL, $alb_id = NULL) {

        $photo = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Photo')->find($id);
        if (NULL != $photo) {
            if ($this->get('security.context')->isGranted('DELETE', $photo)) {
                return $this->handle($this->get('photo_service')->delete($photo));
            } else {
                return $this->handle($this->getView(array('error' => 'Access denied.')));
            }
        }

        return $this->handle($this->getView(array('error' => 'Not fond.')));
    }

    /**
     *  @Route("logged/api/account/{acc_id}/album/{alb_id}/photos/{id}", requirements={"id" = "\d+", "acc_id" = "\d+", "alb_id" = "\d+"})
     * @Method({"PUT"})
     */
    public function likeAction($acc_id, $id, $alb_id) {

        $em = $this->getDoctrine()->getManager();
        $view = View::create();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($acc_id);

        if (NULL === $acc) {
            return $this->handle($view->setData(array('error' => 'Not found')));
        }

        if (NULL != $id) {
            $photo = $em->getRepository('FlashDefaultBundle:Photo')->findByAccountAndId($acc, $id);

            if (NULL != $photo) {
                return $this->handle($this->get('photo_service')->like($photo));
            } else {
                $view->setData(array('error' => 'Not found'));
            }
        } else {
            $view->setData(array('error' => 'Not found'));
        }
        return $this->handle($view);
    }

    /**
     * 
     * @Route("/logged/api/account/{acc_id}/photos/avatar")
     * @Method({"POST"})
     */
    public function postAvatarAction() {

        $acc = $this->get('security.context')->getToken()->getUser();
        $avatars = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Photo')->getAvatarsByAccount($acc);
        $em = $this->getDoctrine()->getManager();

        if (NULL != $avatars) {
            foreach ($avatars as $avatar) {
                $em->persist($avatar);
                $em->remove($avatar);
            }
            $em->flush();
        }

        return $this->handle($this->get('photo_service')->processAvatarForm(new Photo()));
    }

    /**
     * @Route("/logged/api/account/{acc_id}/album/{aName}", requirements={"acc_id" = "\d+"})
     * @Method({"POST"})
     */
    public function createAlbumAction($acc_id, $aName) {

        $acc = $this->get('security.context')->getToken()->getUser();

        return $this->handle($this->get('photo_service')->createAlbum($aName));
    }

    /**
     * @Route("/logged/api/account/{acc_id}/album/{id}", requirements={"acc_id" = "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteAlbumAction($acc_id, $id) {

//        if ($aName == 'avatar' || $aName == 'thumb') {
//            return $this->handle($this->getView(array('error' => 'Access denied')));
//        }

        $album = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:PhotoAlbum')->find($id);

        if (NULL == $album) {
            return $this->handle($this->getView(array('error' => 'Album not found.')));
        }

        if ($this->get('security.context')->isGranted('DELETE', $album)) {
            return $this->handle($this->get('photo_service')->deleteAlbum($album));
        } else {
            return $this->handle($this->getView(array('error' => 'Access denied.')));
        }
    }

}
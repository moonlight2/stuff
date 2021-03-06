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
     * @Route("logged/api/account/{acc_id}/albums/{alb_id}/photos/{id}", requirements={"id" = "\d+", "acc_id" = "\d+", "alb_id" = "\d+"})
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
     * @Route("logged/api/account/{acc_id}/albums/{alb_id}/photos", requirements={"acc_id" = "\d+", "alb_id" = "\d+"})
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

    /**
     * @Route("logged/api/account/{acc_id}/albums/{alb_id}/photos/{id}/save", requirements={"acc_id" = "\d+", "alb_id" = "\d+", "id" = "\d+"})
     * @Route("")
     * @Method({"POST"})
     */
    public function saveAction($acc_id, $alb_id, $id) {

        $em = $this->getDoctrine()->getManager();
        $view = View::create();
        $albRep = $em->getRepository('FlashDefaultBundle:Album');
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($acc_id);
        $ownAcc = $this->get('security.context')->getToken()->getUser();
        $alb = $albRep->find($alb_id);
        

        if (NULL === $acc || NULL === $alb || NULL === $id) {
            return $this->handle($view->setData(array('error' => 'Not found')));
        }

        $name = 'Схраненные фотографии';

        $photo = $em->getRepository('FlashDefaultBundle:Photo')->findByAccountAndId($acc, $id);
        $newPhoto = clone $photo;
        
        $em->detach($photo);

        if ($albRep->exists($name, $ownAcc)) {
            $newAlbum = $albRep->getByAccountAndName($ownAcc, $name);
        } else {
            $newAlbum = $this->get('photo_service')->createAlbum($name);
            $newAlbum->setPreview($newPhoto->getPath());
        }

        $newPhoto->setAccount($ownAcc);
        $newPhoto->setAlbum($newAlbum);
        $newAlbum->addPhoto($newPhoto);
        $em->persist($newPhoto);
        $em->persist($newAlbum);
        $em->flush();

        return $this->handle($view->setData($newPhoto));
    }

    public function putAction($id) {
        
    }

    /**
     *  @Route("logged/api/account/{acc_id}/albums/{alb_id}/photos/{id}", requirements={"id" = "\d+", "alb_id" = "\d+", "acc_id" = "\d+"})
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
     *  @Route("logged/api/account/{acc_id}/albums/{alb_id}/photos/{id}", requirements={"id" = "\d+", "acc_id" = "\d+", "alb_id" = "\d+"})
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
     * 
     * @Route("/logged/api/account/{acc_id}/photos/album/garbage")
     * @Method({"POST"})
     */
    public function postToEventAction() {

        $acc = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        return $this->handle($this->get('photo_service')->processToEventForm(new Photo()));
    }

    /**
     * @Route("/logged/api/account/{acc_id}/albums/{id}", requirements={"id" = "\d+", "acc_id" = "\d+"})
     * @Method({"GET"})
     */
    public function getAlbumAction($acc_id, $id = NULL) {

        $em = $this->getDoctrine()->getManager();
        $view = View::create();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($acc_id);

        if (NULL === $acc) {
            return $this->handle($view->setData(array('error' => 'Not found')));
        }

        if (NULL !== $id) {
            $alb = $em->getRepository('FlashDefaultBundle:Album')->find($id);
            if (NULL != $alb) {
                $view->setData($alb);
            } else {
                $view->setData(array('error' => 'Not found'));
            }
        } else {
            $albums = $em->getRepository('FlashDefaultBundle:Album')->findAllByAccount($acc);
            $view->setData($albums);
        }
        return $this->handle($view);
    }

    /**
     * @Route("/logged/api/account/{acc_id}/albums/{aName}", requirements={"acc_id" = "\d+"})
     * @Method({"POST"})
     */
    public function createAlbumAction($acc_id, $aName) {

        $acc = $this->get('security.context')->getToken()->getUser();
        $view = View::create();
        $view->setData($this->get('photo_service')->createAlbum($aName));
        return $this->handle($view);
    }

    /**
     * @Route("/logged/api/account/{acc_id}/albums/{id}", requirements={"acc_id" = "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteAlbumAction($acc_id, $id) {

        $album = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Album')->find($id);

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
<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\View\View;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;
use Flash\Bundle\DefaultBundle\Entity\Photo;

/**
 * @Route("p{acc_id}/photos", requirements={"acc_id" = "\d+"})
 */
class PhotoApiController extends RESTController implements GenericRestApi {

    /**
     * @Route("/{id}", requirements={"id" = "\d+"})
     * @Method({"GET"})
     */
    public function getAction($acc_id, $id = NULL) {

        $em = $this->getDoctrine()->getManager();
        $view = View::create();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($acc_id);

        if (NULL === $acc) {
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
            $photos = $em->getRepository('FlashDefaultBundle:Photo')->findAllByAccount($acc);
            $view->setData($photos);
        }
        return $this->handle($view);
    }

    /**
     * @Route("")
     * @Method({"POST"})
     */
    public function postAction() {

        return $this->handle($this->get('photo_service')->processForm(new Photo()));
    }

    public function putAction($id) {
        
    }

    /**
     * @Route("/{id}", requirements={"id" = "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteAction($acc_id, $id = NULL) {

        $em = $this->getDoctrine()->getManager();
        if (NULL != $id) {
            $photo = $em->getRepository('FlashDefaultBundle:Photo')->find($id);
            if (NULL != $photo) {
                /* Photo servise will check your rights to remove photo */
                return $this->handle($this->get('photo_service')->delete($photo));
            } else {
                $view->setData(array('error' => 'Not found'));
            }
        } else {
            $view->setData(array('error' => 'Not found'));
        }
    }

    /**
     * @Route("/{id}/like", requirements={"id" = "\d+"})
     * @Method({"POST"})
     */
    public function likeAction($acc_id, $id) {

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

}
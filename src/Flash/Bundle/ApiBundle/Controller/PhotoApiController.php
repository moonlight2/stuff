<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\View\View;
use Flash\Bundle\DefaultBundle\Entity\Group;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;
use Flash\Bundle\DefaultBundle\Entity\Photo;

/**
 * @Route("logged/rest/api/photos")
 */
class PhotoApiController extends RESTController implements GenericRestApi {

    /**
     * @Route("/{id}", requirements={"id" = "\d+"})
     * @Method({"GET"})
     * @return single Account data
     */
    public function getAction($id = NULL) {

        $view = View::create();

        if (NULL != $id) {
            $photo = $this->getDoctrine()->getManager()
                            ->getRepository('FlashDefaultBundle:Photo')->find($id);
            if (NULL != $photo) {
                $view->setData($photo);
            } else {
                $view->setData(array('error' => 'Not found'));
            }
        } else {

            $photos = $this->getDoctrine()->getManager()
                            ->getRepository('FlashDefaultBundle:Photo')->findAll();
            $view->setData($photos);
        }
        return $this->handle($view);
    }

    /**
     * @Route("/{id}/like", requirements={"id" = "\d+"})
     * @Method({"POST"})
     */
    public function likeAction($id) {

        return $this->handle($this->get('photo_service')->like($id));
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

    public function deleteAction($id) {

        return $this->handle($this->get('photo_service')->delete($id));
    }

}
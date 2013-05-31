<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;
use FOS\RestBundle\View\View;
use Flash\Bundle\DefaultBundle\Entity\Photo;

/**
 * @Route("/{acc_id}/files")
 */
class FileLoadApiController extends RESTController implements GenericRestApi {

    /**
     * @Route("/{id}", requirements={"id" = "\d+"})
     * @Method({"GET"})
     */
    public function getAction($id = null) {
        $view = View::create();
        $view->setData(array('success' => 'Upload action'));


        return $this->handle($view);
    }

    /**
     * @Route("")
     * @Method({"POST"})
     */
    public function postAction() {

        return $this->handle($this->get('photo_service')->processForm(new Photo()));
    }

    /**
     * @Route("/avatar")
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

    public function putAction($id) {
        
    }

    /**
     * @Route("/{id}", requirements={"id" = "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteAction($id) {

        return $this->handle($this->get('photo_service')->delete($id));
    }

}

?>

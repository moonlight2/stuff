<?php

namespace Flash\Bundle\ApiBundle\Controller\Comment;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Flash\Bundle\DefaultBundle\Entity\Event;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;
use FOS\RestBundle\View\View;

/**
 * @Route("p{acc_id}/photo", requirements={"acc_id" = "\d+"} )
 */
class PhotoCommentApiController extends RESTController implements GenericRestApi {

    /**
     * @Route("/{id}/comment/{comm_id}", requirements={"id" = "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteAction($id, $comm_id = null) {
        
        
        $em = $this->getDoctrine()->getManager();
        if (NULL != $comm_id) {
            $comment = $em->getRepository('FlashDefaultBundle:Comment\PhotoComment')->find($comm_id);
            if (NULL != $comment) {
                /* Comment servise will check your rights to remove photo */
                return $this->handle($this->get('comment_service')->delete($comment));
            } else {
                $view->setData(array('error' => 'Not found'));
            }
        } else {
            $view->setData(array('error' => 'Not found'));
        }
    }

    /**
     * @param id - id of photo
     * @Route("/{id}/comment", requirements={"id" = "\d+"})
     * @Method({"GET"})
     */
    public function getAction($id = NULL) {

        $view = View::create();

        if (NULL != $id) {
            $photo = $this->getDoctrine()->getManager()
                            ->getRepository('FlashDefaultBundle:Photo')->find($id);
            if (NULL != $photo) {
                $view->setData($photo->getComments()->getValues());
            } else {
                $view->setData(array('error' => 'Not found'));
            }
        } else {
            $comments = $this->getDoctrine()->getManager()->
                            getRepository('FlashDefaultBundle:Comment\PhotoComment')->findAll();
            $view->setData($comments);
        }
        return $this->handle($view);
    }

    /**
     * @Route("/comment")
     * @Method({"POST"})
     */
    public function postAction() {

        $acc = $this->get('security.context')->getToken()->getUser();

        $comment = new \Flash\Bundle\DefaultBundle\Entity\Comment\PhotoComment($acc);

        return $this->handle($this->get('comment_service')
                                ->processFormForPhotoComment($comment));
    }

    public function putAction($id) {
        
    }

}
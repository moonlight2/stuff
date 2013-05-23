<?php

namespace Flash\Bundle\ApiBundle\Controller\Comment;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Flash\Bundle\DefaultBundle\Entity\Event;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;
use FOS\RestBundle\View\View;

/**
 * @Route("/logged/rest/api/photo/comments")
 */
class PhotoCommentApiController extends RESTController implements GenericRestApi {

    public function deleteAction($id) {
        
    }

    /**
     * @param id - id of photo
     * @Route("/{id}", requirements={"id" = "\d+"})
     * @Method({"GET"})
     */
    public function getAction($id = NULL) {

        $view = View::create();

        if (NULL != $id) {
            $photo = $this->getDoctrine()->getManager()
                    ->getRepository('FlashDefaultBundle:Comment\PhotoComment')->find($id);
            if (NULL != $comment) {
                $view->setData($comment);
            } else {
                $view->setData(array('error'=>'Not found'));
            }
        } else {
            $comments = $this->getDoctrine()->getManager()->
                            getRepository('FlashDefaultBundle:Comment\PhotoComment')->findAll();
            $view->setData($comments);
        }
        return $this->handle($view);
    }

    /**
     * @Route("")
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
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
     * @Param id - id of photo
     * @Route("/{id}", requirements={"id" = "\d+"})
     * @Method({"GET"})
     */
    public function getAction($id) {

        $view = View::create();

        if (null != $id) {
//            $event = $this->getDoctrine()->getManager()->getRepository('FlashDefaultBundle:Event')->find($id);
//            if (NULL != $event) {
//                $view->setData($event);
//            } else {
//                throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
//            }
        } else {
            $comments = $this->getDoctrine()->getManager()->getRepository('FlashDefaultBundle:Comment')->findAll();
            $view->setData($events);
        }
        return $this->handle($view);
    }

    public function postAction() {
        
    }

    public function putAction($id) {
        
    }

}
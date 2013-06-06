<?php

namespace Flash\Bundle\DefaultBundle\Services\Entity;

use FOS\RestBundle\View\View;
use Flash\Bundle\DefaultBundle\Form\CommentType;
use Flash\Bundle\DefaultBundle\Services\CommonService;
use Flash\Bundle\DefaultBundle\Entity\Comment\PhotoComment;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class CommentService extends CommonService {

    public function processFormForPhotoComment($comment) {

        $request = $this->injector->getRequest();
        $em = $this->injector->getDoctrine()->getManager();
        $form = $this->injector->getForm()->create(new CommentType(), $comment);
        $view = View::create();

        $form->bind($this->getFromRequest(array('text')));

        if ($form->isValid()) {

            $photoId = $request->get('photo_id');

            $photo = $em->getRepository('FlashDefaultBundle:Photo')->find($photoId);

            if (NULL === $photo) {
                return $view->setData(array('error' => 'not found'));
            }

            $comment->setPhoto($photo);
            $photo->addComment($comment);

            $em->persist($photo);
            $em->persist($comment);
            $em->flush();

            if ($request->getMethod() == 'POST') {
                $acl = $this->injector->getAcl();
                $acl->grant($comment, MaskBuilder::MASK_EDIT);
                $acl->grant($comment, MaskBuilder::MASK_DELETE);
            }
        } else {
            $view->setStatusCode(400);
            return $view->setData($this->getErrorMessages($form));
        }
        return $view->setData($comment);
    }

    public function delete($comment) {

        $em = $this->injector->getDoctrine()->getManager();

        $em->persist($comment);
        $em->remove($comment);
        $em->flush();

        return View::create()->setData(array('success' => 'Comment was deleted'));
    }

}

?>
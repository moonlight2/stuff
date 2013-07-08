<?php

namespace Flash\Bundle\DefaultBundle\Services\Entity;

use FOS\RestBundle\View\View;
use Flash\Bundle\DefaultBundle\Form\PhotoType;
use Flash\Bundle\DefaultBundle\Services\CommonService;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class PhotoService extends CommonService {

    public function processForm($photo, $albumName = NULL) {

        $request = $this->injector->getRequest();
        $em = $this->injector->getDoctrine()->getManager();
        $form = $this->injector->getForm()->create(new PhotoType(), $photo);
        $view = View::create();

        $form->bind(array(
            'file' => $request->files->get('qqfile')
        ));

        if ($form->isValid()) {

            $acc = $this->context->getToken()->getUser();

            $file = $request->files->get('qqfile');
            $photo->setFile($file);
            $photo->setAccount($acc);

            if (NULL != $albumName) {
                $photo->setAlbum($albumName);
            }

            $em->persist($photo);
            $em->flush();

            if ($request->getMethod() == 'POST') {
                $acl = $this->injector->getAcl();
                $acl->grant($photo, MaskBuilder::MASK_EDIT);
                $acl->grant($photo, MaskBuilder::MASK_VIEW);
                $acl->grant($photo, MaskBuilder::MASK_DELETE);
            }
            $response = $photo;
        } else {
            $view->setStatusCode(400);
            $response = $this->getErrorMessages($form);
        }
        return $view->setData(array('success' => 'true', 'photo' => $photo));
    }

    public function processAvatarForm($photo) {

        $request = $this->injector->getRequest();
        $em = $this->injector->getDoctrine()->getManager();
        $form = $this->injector->getForm()->create(new PhotoType(), $photo);
        $view = View::create();

        $form->bind(array(
            'file' => $request->files->get('qqfile')
        ));

        if ($form->isValid()) {

            $acc = $this->context->getToken()->getUser();

            $file = $request->files->get('qqfile');
            $photo->setFile($file);
            $photo->setAccount($acc);
            $photo->setAvatar(true);

            $em->persist($photo);

            $em->flush();

            if ($request->getMethod() == 'POST') {
                $acl = $this->injector->getAcl();
                $acl->grant($photo, MaskBuilder::MASK_EDIT);
                $acl->grant($photo, MaskBuilder::MASK_VIEW);
                $acl->grant($photo, MaskBuilder::MASK_DELETE);
            }
            $response = $photo;
        } else {
            $view->setStatusCode(400);
            $response = $this->getErrorMessages($form);
        }
        return $view->setData(array('success' => 'true', 'photo' => $photo));
    }

    public function createAlbum($aName) {

        $view = View::create();
        $em = $this->injector->getDoctrine()->getManager();
        $acc = $this->context->getToken()->getUser();

        $album = new \Flash\Bundle\DefaultBundle\Entity\PhotoAlbum($aName);
        $album->setAccount($acc);
        $acc->addPhotoAlbum($album);

        $em->persist($album);
        $em->persist($acc);
        $em->flush();

        $acl = $this->injector->getAcl();
        $acl->grant($album, MaskBuilder::MASK_EDIT);
        $acl->grant($album, MaskBuilder::MASK_VIEW);
        $acl->grant($album, MaskBuilder::MASK_DELETE);

        return $view->setData(array('success' => 'true'));
    }

    public function deleteAlbum($album) {

        $em = $this->injector->getDoctrine()->getManager();
        $view = View::create();

//        $photos = $album->getPhotos()->getValues();
//        if ($album->getPhotos()->count() > 0) {
//            foreach ($photos as $photo) {
//                $this->delete($photo);
//            }
//        }

        $em->persist($album);
        $em->remove($album);
        $em->flush();
        
        return $view->setData(array('success'=>'Album was deleted'));
    }

    public function like($photo) {

        $em = $this->injector->getDoctrine()->getManager();
        $view = View::create();
        $acc = $this->context->getToken()->getUser();

        if ($photo->existsRating($acc)) {
            $acc->removePhotoLike($photo);
            $photo->removeRating($acc);
        } else {
            $acc->setPhotoLike($photo);
            $photo->addRating($acc);
        }

        $em->persist($acc);
        $em->persist($photo);

        $em->flush();

        return $view->setData($photo);
    }

    public function delete($photo) {

        $em = $this->injector->getDoctrine()->getManager();
        $view = View::create();

        if ($this->context->isGranted('DELETE', $photo)) {

            if ($photo->getRating()->count() > 0) {
                $accs = $photo->getRating()->getValues();
                foreach ($accs as $acc) {
                    $acc->removePhotoLike();
                    $em->persist($acc);
                }
                $photo->clearRating();
            }
            $em->persist($photo);
            $em->remove($photo);
            $em->flush();

            $resp = array('success' => 'Photo was deleted');
        } else {
            $resp = array('error' => "Access denied. You don't have enought permissions");
        }
        return $view->setData($resp);
    }

}

?>
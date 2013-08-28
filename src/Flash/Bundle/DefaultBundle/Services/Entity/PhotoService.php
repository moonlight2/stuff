<?php

namespace Flash\Bundle\DefaultBundle\Services\Entity;

use FOS\RestBundle\View\View;
use Flash\Bundle\DefaultBundle\Form\PhotoType;
use Flash\Bundle\DefaultBundle\Services\CommonService;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class PhotoService extends CommonService {

    public function processForm($photo, $albumId = NULL) {

        $request = $this->injector->getRequest();
        $em = $this->injector->getDoctrine()->getManager();
        $form = $this->injector->getForm()->create(new PhotoType(), $photo);
        $view = View::create();
        $album = $em->getRepository('FlashDefaultBundle:Album')->find($albumId);

        if (NULL == $album) {
            return $view->setData(array('error' => 'Not found'));
        }

        $form->bind(array(
            'file' => $request->files->get('qqfile')
        ));

        if ($form->isValid()) {

            $acc = $this->context->getToken()->getUser();

            $file = $request->files->get('qqfile');
            $photo->setFile($file);
            $photo->setAccount($acc);
            $photo->setAlbum($album);
            if ($album->getPhotos()->count() > 0) {
                $photos = $album->getPhotos()->getValues();
                $p = $photos[0];
                $album->setPreview($p->getPath());
            }
            $album->addPhoto($photo);

            $em->persist($album);
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

        $this->createUserEvent($photo);

        return $view->setData(array('success' => 'true', 'photo' => $photo));
    }

    private function createUserEvent($photo, $limit = NULL) {

        $photoLimit = (NULL == $limit) ? 14 : $limit;

        $acc = $this->context->getToken()->getUser();
        $em = $this->injector->getDoctrine()->getManager();

        $todayPhotos = $em->getRepository('FlashDefaultBundle:Photo')->getOwnTodaysPhoto($acc);
        $uEvent = null;
        $isLimit = false;


        if ((sizeof($todayPhotos) <= $photoLimit)) {
            $todayEvents = $em->getRepository('FlashDefaultBundle:UserEvent')
                    ->getOwnTodaysEvents($acc, 'photo');
            if (null != $todayEvents) {
                foreach ($todayEvents as $e) {
                    if ($this->compareDates($photo->getUploaded(), new \DateTime($e['edate']))) {
                        $uEvent = $em->getRepository('FlashDefaultBundle:UserEvent')->find($e['id']);
                        break;
                    }
                }
            }
        } else {
            $isLimit = true;
        }

        if (!$isLimit) {
            if (sizeof($todayPhotos > 1)) {
                if (null == $uEvent) {
                    $uEventFactory = $this->injector->getUserEventFactory();
                    $uEvent = $uEventFactory->get($uEventFactory::NEW_PHOTO, $acc, $photo);
                } else {
                    $src = 'image/thumb/' . $acc->getId() . '/' . $photo->getPath();
                    $href = 'p' . $acc->getId() . '/gallery#album/' . $photo->getAlbum()->getId() . '/photo/' . $photo->getId();
                    $uEvent->addToDescription('<a href="' . $href . '"><img src="' . $src . '" /></a>'); // create new description 
                }
            } else {
                $uEventFactory = $this->injector->getUserEventFactory();
                $uEvent = $uEventFactory->get($uEventFactory::NEW_PHOTO, $acc, $photo);
            }
            $em->persist($uEvent);
            $em->flush();
            $acl = $this->injector->getAcl();
            $acl->grant($uEvent, MaskBuilder::MASK_DELETE);
        }
    }


    private function compareDates($d1, $d2) {
        return $d1->format('Y') == $d2->format('Y') &&
                $d1->format('m') == $d2->format('m') &&
                $d1->format('d') == $d2->format('d');
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

    public function processToEventForm($photo) {

        $request = $this->injector->getRequest();
        $acc = $this->context->getToken()->getUser();
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
            $photo->setGarbage(true);

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


        $em = $this->injector->getDoctrine()->getManager();
        $acc = $this->context->getToken()->getUser();

        $album = new \Flash\Bundle\DefaultBundle\Entity\Album($aName);
        $album->setAccount($acc);
        $acc->addAlbum($album);

        $em->persist($album);
        $em->persist($acc);
        $em->flush();

        $acl = $this->injector->getAcl();
        $acl->grant($album, MaskBuilder::MASK_EDIT);
        $acl->grant($album, MaskBuilder::MASK_VIEW);
        $acl->grant($album, MaskBuilder::MASK_DELETE);

        return $album;
    }

    public function deleteAlbum($album) {

        $em = $this->injector->getDoctrine()->getManager();
        $view = View::create();

        $photos = $album->getPhotos()->getValues();
        if ($album->getPhotos()->count() > 0) {
            foreach ($photos as $photo) {
                $this->delete($photo);
            }
        }

        $em->persist($album);
        $em->remove($album);
        $em->flush();

        return $view->setData(array('success' => 'Album was deleted'));
    }

    public function like($photo) {

        $em = $this->injector->getDoctrine()->getManager();
        $view = View::create();
        $acc = $this->context->getToken()->getUser();

        if (!$photo->existsRating($acc)) {
            $photo->addRating($acc);
            $acc->addPhotoLike($photo);
        } else {
            $photo->removeRating($acc);
            $acc->removePhotoLike($photo);
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

            if ($photo->getRatingCount() > 0) {
                $accs = $photo->getRating()->getValues();
                foreach ($accs as $acc) {
                    $acc->removePhotoLike($photo);
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
<?php

namespace Flash\Bundle\DefaultBundle\Services\Entity;

use FOS\RestBundle\View\View;
use Flash\Bundle\DefaultBundle\Form\PhotoType;
use Flash\Bundle\DefaultBundle\Services\CommonService;

class PhotoService extends CommonService {

    public function processForm($photo) {

        $request = $this->injector->getRequest();
        $em = $this->injector->getDoctrine()->getManager();
        $form = $this->injector->getForm()->create(new PhotoType(), $photo);
        $view = View::create();

        $form->bind(array(
//            'name' => $request->get('name'),
            'file' => $request->files->get('qqfile')
        ));

        if ($form->isValid()) {

            $acc = $this->context->getToken()->getUser();

            $file = $request->files->get('qqfile');
            $photo->setFile($file);
            $photo->setAccount($acc);

            $em->persist($photo);
            $em->flush();
        } else {
            $view->setStatusCode(400);
            return $view->setData($this->getErrorMessages($form));
        }
        return array('success' => 'true');
    }

}

?>
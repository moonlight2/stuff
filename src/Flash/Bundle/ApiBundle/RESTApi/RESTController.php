<?php

namespace Flash\Bundle\ApiBundle\RESTApi;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;

abstract class RESTController extends FOSRestController implements ClassResourceInterface {

    protected function getFromRequest($data) {

        $request = $this->getRequest();

        foreach ($data as $el) {
            $resp[$el] = $request->get($el);
        }
        return $resp;
    }

    protected function handle($view) {
        return $this->get('fos_rest.view_handler')
                        ->createResponse($view, $this->getRequest(), $this->getContentType());
    }

    private function getContentType() {

        $formats = $this->getRequest()->getAcceptableContentTypes();

        foreach ($formats as $format) {
            if ($format = 'application/json') {
                return 'json';
            }
        }
        return null;
    }

    protected function getErrorMessages(\Symfony\Component\Form\Form $form) {
        $errors = array();

        if ($form->hasChildren()) {
            foreach ($form->getChildren() as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getErrorMessages($child);
                }
            }
        } else {
            foreach ($form->getErrors() as $key => $error) {
                $errors[] = $error->getMessage();
            }
        }
        return $errors;
    }

}

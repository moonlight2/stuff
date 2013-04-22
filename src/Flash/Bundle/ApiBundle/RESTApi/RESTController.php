<?php

namespace Flash\Bundle\ApiBundle\RESTApi;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Flash\Bundle\DefaultBundle\Lib\Array2XML;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;

abstract class RESTController extends FOSRestController implements ClassResourceInterface {

    protected function getFromRequest($data) {

        $request = $this->getRequest();

        foreach ($data as $el) {
            $resp[$el] = $request->get($el);
        }
        return $resp;
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
//        $errors['success'] ='false';
        return $errors;
    }

}

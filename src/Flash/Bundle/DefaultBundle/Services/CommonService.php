<?php

namespace Flash\Bundle\DefaultBundle\Services;

abstract class CommonService {
    

    protected function getFromRequest($request, $data) {

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
        return $errors;
    }

}

?>

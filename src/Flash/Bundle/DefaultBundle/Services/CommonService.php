<?php

namespace Flash\Bundle\DefaultBundle\Services;

use Symfony\Component\Security\Core\SecurityContext;
use Flash\Bundle\DefaultBundle\Services\RequestInjector;

abstract class CommonService {

    protected $context;
    protected $injector;

    public function __construct(SecurityContext $context, RequestInjector $injector) {
        
        $this->context = $context;
        $this->injector = $injector;
    }

    protected function getFromRequest($data) {
        
        $request = $this->injector->getRequest();
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

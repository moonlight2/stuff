<?php

namespace Flash\Bundle\DefaultBundle\Services\Entity;

use FOS\RestBundle\View\View;
use Flash\Bundle\DefaultBundle\Form\GroupType;
use Flash\Bundle\DefaultBundle\Services\CommonService;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class GroupService extends CommonService {

    public function processForm($group) {

        $request = $this->injector->getRequest();
        $em = $this->injector->getDoctrine()->getManager();
        $form = $this->injector->getForm()->create(new GroupType(), $group);
        $view = View::create();

        $form->bind($this->getFromRequest(array('name', 'city', 'country')));

        if ($form->isValid()) {

            $acc = $this->context->getToken()->getUser();
 
            if (NULL != $acc->getGroup()) {
                $view->setStatusCode(400);
                return $view->setData(
                                array('error' => 'User is already in group ' .
                                    $acc->getGroup()->getName())
                );
            }

            $group->addAccount($acc);
            $acc->setGroup($group);
            $em->persist($group);
            $em->flush();

            if ($request->getMethod() == 'POST') {
                $acl = $this->injector->getAcl();
                $acl->grant($group, MaskBuilder::MASK_EDIT);
                $acl->grant($group, MaskBuilder::MASK_VIEW);
                $acl->grant($group, MaskBuilder::MASK_DELETE);
            }
        } else {
            $view->setStatusCode(400);
            return $view->setData($this->getErrorMessages($form));
        }
        return $group;
    }

}
<?php

namespace Flash\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller {

    /**
     * @Route("/main", name="main_page")
     * @Template()
     */
    public function mainAction($id = null) {

        $user = $this->get('security.context')->getToken()->getUser();

        return array(
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName()
        );
    }

}

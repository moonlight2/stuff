<?php

namespace Flash\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class RegistrationController extends Controller {

    /**
     * @Route("/regist", name="_flash_registration")
     * @Template()
     */
    public function regAction($id = null) {

        return array();
    }
    


}

<?php

namespace Flash\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class RegistrationController extends Controller {

    /**
     * @Route("/regist")
     * @Template()
     */
    public function regAction($id = null) {

        return array();
    }
    


}

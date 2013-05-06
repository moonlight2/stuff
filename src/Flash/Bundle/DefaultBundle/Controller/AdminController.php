<?php

namespace Flash\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class AdminController extends Controller {

    /**
     * @Route("/admin", name="_flash_admin")
     * @Template()
     */
    public function mainAction($id = null) {

        return new \Symfony\Component\HttpFoundation\Response("This is admin page");
    }
    
}
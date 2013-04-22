<?php

namespace Flash\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Flash\Bundle\DefaultBundle\Entity\Account;

class DefaultController extends Controller {

    /**
     * @Route("/rest/{id}", name="_default_rest")
     * @Template()
     */
    public function restAction($id = null) {

        return array('name' => 'Supernameww');
    }
    
    
    /**
     * @Route("/registration")
     * @Template()
     */
    public function registerAction($id = null) {

        return array();
    }
    


}

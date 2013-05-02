<?php

namespace Flash\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Flash\Bundle\DefaultBundle\Entity\Account;

class DefaultController extends Controller {

    /**
     * @Route("/main", name="main_page")
     * @Template()
     */
    public function mainAction($id = null) {

        $user = $this->get('security.context')->getToken()->getUser();
        
        return array('name' => $user->getUsername());
    }
    
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

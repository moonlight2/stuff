<?php

namespace Flash\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/template")
 */
class TemplateController extends Controller {

    /**
     * @Route("/header", name="_template_header")
     */
    public function headerAction() {
        return ($this->render(
        'FlashDefaultBundle:Default:js/header.html.twig', array('name'=>'name')));
    }
    
    /**
     * @Route("/wine-list-item", name="_template_list")
     */
    public function listItemAction() {
        return ($this->render(
        'FlashDefaultBundle:Default:js/wine-list-item.html.twig', array('name'=>'name')));
    }
    
    /**
     * @Route("/wine-details", name="_template_details")
     */
    public function wineDetailsAction() {
        return ($this->render(
        'FlashDefaultBundle:Default:js/wine-details.html.twig', array('name'=>'name')));
    }
}

?>
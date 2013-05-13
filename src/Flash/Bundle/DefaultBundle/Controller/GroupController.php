<?php

namespace Flash\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class GroupController extends Controller {

    /**
     * @Route("/group{id}", name="_group_page")
     * @Template()
     */
    public function groupAction($id = null) {

        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $group = $em->getRepository('FlashDefaultBundle:Group')->find($id);

        return array('group_name' => $group->getName(), 'user' => $user);
    }

    /**
     * @Route("/my_group", name="_my_group_page")
     * @Template()
     */
    public function groupRouterAction() {

        $user = $this->get('security.context')->getToken()->getUser();
        $groupId = $user->getGroup()->getId();

        return $this->forward('FlashDefaultBundle:Group:group', array(
                    'id' => $groupId
        ));
    }

}

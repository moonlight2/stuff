<?php

namespace Acme\Bundle\TestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Flash\Bundle\DefaultBundle\Entity\Account;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\View\View,
    FOS\RestBundle\View\ViewHandler,
    FOS\RestBundle\View\RouteRedirectView;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference,
    Symfony\Component\Routing\Exception\ResourceNotFoundException,
    Symfony\Component\Validator\ValidatorInterface;

class GroupController extends FOSRestController {

    /**
     * Collection get action
     * @var Request $request
     * @return array
     *
     * @Rest\View()
     */
    public function getAction(\Symfony\Component\HttpFoundation\Request $request) {

        $em = $this->getDoctrine()->getManager();
        $groups = $em->getRepository('FlashDefaultBundle:Group')->findAll();

        return array('groups' => $groups);
    }

    /**
     * Get action
     * @var integer $id Id of the entity
     * @return array
     *
     * @Rest\View()
     */
    public function getOneAction($id) {
        $entity = $this->getGroup($id);

        return array(
            'entity' => $entity,
        );
    }

    /**
     * Collection post action
     * @var Request $request
     * @return View|array
     */
    public function cpostAction(Request $request) {

        $entity = new \Flash\Bundle\DefaultBundle\Entity\Group();

        $form = $this->createForm(new \Flash\Bundle\DefaultBundle\Form\GroupTypeType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectView(
                            $this->generateUrl(
                                    'get_group', array('id' => $entity->getId())
                            ), Codes::HTTP_CREATED
            );
        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Put action
     * @var Request $request
     * @var integer $id Id of the entity
     * @return View|array
     */
    public function putAction(Request $request, $id) {

        $entity = $this->getGroup($id);

        $form = $this->createForm(new \Flash\Bundle\DefaultBundle\Form\GroupTypeType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->view(null, Codes::HTTP_NO_CONTENT);
        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Delete action
     * @var integer $id Id of the entity
     * @return View
     */
    public function deleteAction($id) {
        $entity = $this->getGroup($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();

        return $this->view(null, Codes::HTTP_NO_CONTENT);
    }

    private function getGroup($id) {

        $em = $this->getDoctrine()->getManager();

        $group = $em->getRepository('FlashDefaultBundle:Group')->find($id);

        if (!$group) {
            throw $this->createNotFoundException('Unable to find organisation entity');
        }

        return $group;
    }

}
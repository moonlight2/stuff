<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Flash\Bundle\DefaultBundle\Entity\Wine;

/**
 * @Route("/rest/api")
 */
class UserApiController extends Controller implements GenericRestApi
{
    /**
     * @Route("/wines/{id}")
     * @Method({"PUT"})
     */
    public function putAction(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
        $wine = $em->getRepository('FlashDefaultBundle:Wine')->find($data['id']);

        $wine->setName($data['name']);
        $wine->setGrapes($data['grapes']);
        $wine->setCountry($data['country']);
        $wine->setRegion($data['region']);
        $wine->setYear($data['year']);
        $wine->setDescription($data['description']);
        $wine->setPicture($data['picture']);

        $em->persist($wine);
        $em->flush();

        return new JsonResponse($data);
    }

    /**
     * @Route("/wines")
     * @Method({"POST"})
     */
    public function postAction(Request $request) {
        

        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);

        $wine = new Wine();
        
        $wine->setName($data['name']);
        $wine->setGrapes($data['grapes']);
        $wine->setCountry($data['country']);
        $wine->setRegion($data['region']);
        $wine->setYear($data['year']);
        $wine->setDescription($data['description']);
        $wine->setPicture($data['picture']);

        $em->persist($wine);
        $em->flush();
        $data['id'] = $wine->getId();

        return new JsonResponse($data);
    }

    /**
     * @Route("/wines/{id}")
     * @Method({"DELETE"})
     */
    public function deleteAction($id) {

        $em = $this->getDoctrine()->getManager();

        $wine = $em->getRepository('FlashDefaultBundle:Wine')->find($id);

        $em->persist($wine);
        $em->remove($wine);
        $em->flush();
        return new JsonResponse(array($id => 'deleted'));
    }

    /**
     * @Route("/wines/{id}")
     * @Method({"GET"})
     */
    public function getAction($id = null) {

        $em = $this->getDoctrine()->getManager();

        if (null != $id) {
            $wine = $em->getRepository('FlashDefaultBundle:Wine')->find($id);

            $resp['id'] = $wine->getId();
            $resp['name'] = $wine->getName();
            $resp['grapes'] = $wine->getGrapes();
            $resp['country'] = $wine->getCountry();
            $resp['region'] = $wine->getRegion();
            $resp['year'] = $wine->getYear();
            $resp['description'] = $wine->getDescription();
            $resp['picture'] = $wine->getPicture();

            return new JsonResponse($resp);
        } else {
            $wines = $em->getRepository('FlashDefaultBundle:Wine')->findAll();
            $resp = array();
            $end = array();
            foreach ($wines as $wine) {
                $resp['id'] = $wine->getId();
                $resp['name'] = $wine->getName();
                $resp['grapes'] = $wine->getGrapes();
                $resp['country'] = $wine->getCountry();
                $resp['region'] = $wine->getRegion();
                $resp['year'] = $wine->getYear();
                $resp['description'] = $wine->getDescription();
                $resp['picture'] = $wine->getPicture();
                $end[] = $resp;
            }

            return new JsonResponse($end);
        }
    }

}

?>


<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Flash\Bundle\DefaultBundle\Entity\Event;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/logged/rest/api/files")
 */
class FileLoadApiController extends RESTController implements GenericRestApi {

    public function deleteAction($id) {
        
    }

    /**
     * @Route("/{id}", requirements={"id" = "\d+"})
     * @Method({"GET"})
     */
    public function getAction($id = null) {
        $view = View::create();
        $view->setData(array('success' => 'Upload action'));

        return $this->handle($view);
    }

    protected function getErrorMessages(\Symfony\Component\Form\Form $form) {

        $errors = array();

        if ($form->hasChildren()) {
            foreach ($form->getChildren() as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getErrorMessages($child);
                }
            }
        } else {
            foreach ($form->getErrors() as $key => $error) {
                $errors[] = $error->getMessage();
            }
        }
        return $errors;
    }

    /**
     * @Route("")
     * @Method({"POST"})
     */
    public function postFileAction(Request $request) {

        $document = new \Flash\Bundle\DefaultBundle\HttpFoundation\File\UploadedFile\Document();
        $form = $this->createFormBuilder($document)
                ->add('name')
                ->add('file')
                ->getForm();



        $form->bindRequest($request);

        if ($form->isValid()) {
            print_r($document);
        } else {
            $this->getErrorMessages($form);
        }

        exit('no');



        print_r($request);
//        print_r($file->getType());


        var_dump($document->getFile());

        exit();
        return array('success' => 'Upload action');
    }

    public function putAction($id) {
        
    }

    public function postAction() {
        
    }

}

?>

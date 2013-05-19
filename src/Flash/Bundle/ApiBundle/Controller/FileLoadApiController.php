<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Flash\Bundle\DefaultBundle\Entity\Event;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Flash\Bundle\DefaultBundle\HttpFoundation\File\UploadedFile\Document;

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

        $document = new Document();

        $request = $this->getRequest();

        /* @var UploadedFile */
        $uploadedFile = $request->files->get('qqfile');
        
        print_r($uploadedFile->getClientMimeType());
        $uploadedFile->move(__DIR__."/../../../../uploads", $uploadedFile->getClientOriginalName());
        exit();

    }

    public function putAction($id) {
        
    }

    public function postAction() {
        
    }

}

?>

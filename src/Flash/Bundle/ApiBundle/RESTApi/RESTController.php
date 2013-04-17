<?php

namespace Flash\Bundle\ApiBundle\RESTApi;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Flash\Bundle\DefaultBundle\Lib\Array2XML;

abstract class RESTController extends Controller {

    /**
     * @return JSON, XML, html
     */
    public function responseDataTypeAction($data, $type = null) {

        switch ($type) {
            case ".json":
                return new JsonResponse($data);
            case ".xml":
                echo Array2XML::createXML('root', $data)->saveXML();
                exit();
            case ".txt":
                echo "<pre>";
                print_r($data);
                echo "</pre>";
                exit();
            default :
                return new JsonResponse($data);
        }
    }

}

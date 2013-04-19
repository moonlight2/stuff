<?php

namespace Flash\Bundle\ApiBundle\RESTApi;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Flash\Bundle\DefaultBundle\Lib\Array2XML;
use Symfony\Component\HttpFoundation\Response;

abstract class RESTController extends Controller {

    protected function responce($obj, $format, $status = 200) {

        $serializer = $this->get('jms_serializer');

        switch ($format) {
            case '.xml':
                $data = $serializer->serialize($obj, 'xml');
                $headers = array('Content-Type'=>'application/xml');
                break;
            case '.json':
                $data = $serializer->serialize($obj, 'json');
                $headers = array('Content-Type'=>'application/json');
                break;
            case '.yml':
                $data = $serializer->serialize($obj, 'yml');
                $headers = array('Content-Type'=>'text/x-yaml'); 
                break;
            default:
                $data = $serializer->serialize($obj, 'json');
                $headers = array('Content-Type'=>'application/json');
        }

        $responce = new Response($data, $status, $headers);

        return $responce;
    }

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

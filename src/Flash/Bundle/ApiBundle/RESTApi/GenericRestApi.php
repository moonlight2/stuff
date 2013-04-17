<?php

namespace Flash\Bundle\ApiBundle\RESTApi;
use Symfony\Component\HttpFoundation\Request;

interface GenericRestApi 
{
    public function putAction(Request $request, $type);
    
    public function postAction(Request $request, $type);
    
    public function deleteAction($id, $type);
    
    public function getAction($id, $type);
}
?>

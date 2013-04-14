<?php

namespace Flash\Bundle\ApiBundle\Controller;
use Symfony\Component\HttpFoundation\Request;

interface GenericRestApi
{
    public function putAction(Request $request);
    
    public function postAction(Request $request);
    
    public function deleteAction($id);
    
    public function getAction($id);
}
?>

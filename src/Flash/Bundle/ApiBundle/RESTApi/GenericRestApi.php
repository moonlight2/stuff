<?php

namespace Flash\Bundle\ApiBundle\RESTApi;

interface GenericRestApi 
{
    public function getAction($id);
    
    public function postAction();
    
    public function deleteAction($id);
    
    public function putAction($id);
}
?>

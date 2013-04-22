<?php

namespace Flash\Bundle\ApiBundle\RESTApi;

interface GenericRestApi 
{
    public function putAction($id);
    
    public function postAction();
    
    public function deleteAction($id);
    
    public function getAction($id);
}
?>

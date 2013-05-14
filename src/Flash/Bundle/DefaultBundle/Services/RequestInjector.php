<?php

namespace Flash\Bundle\DefaultBundle\Services;

class RequestInjector {

    protected $container;

    public function __construct(\Symfony\Component\DependencyInjection\ContainerInterface $container) {
        $this->container = $container;
    }

    public function getRequest() {
        return $this->container->get('request');
    }
    
    public function getToken() {
        return $this->container->get('user');
    }

    public function getDoctrine() {
        return $this->container->get('doctrine');
    }

    public function getForm() {
        return $this->container->get('form.factory');
    }

    public function getAcl() {
        return $this->container->get('acl_service');
    }

}
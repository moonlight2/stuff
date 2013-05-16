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

    public function getDoctrine() {
        return $this->container->get('doctrine');
    }

    public function getForm() {
        return $this->container->get('form.factory');
    }

    public function getAcl() {
        return $this->container->get('acl_manager');
    }

    public function getSecurityEncoderFactory() {
        return $this->container->get('security.encoder_factory');
    }
    
    public function getUserEvent() {
        return $this->container->get('user_event');
    }
}
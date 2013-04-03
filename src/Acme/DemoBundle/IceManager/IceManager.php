<?php

namespace Acme\DemoBundle\IceManager;


class IceManager
{
    private $ice;
    
    public function __construct($ice) {
        $this->ice = $ice;
    }
    
    public function get() {
        return $this->ice;
    }
    
}

?>

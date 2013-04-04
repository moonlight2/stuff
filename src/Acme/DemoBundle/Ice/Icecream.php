<?php

namespace Acme\DemoBundle\Ice;


class Icecream
{
    private $ice;
    
    public function __construct($ice) {
        $this->ice = $ice;
    }
    
    public function getIce() {
        return $this->ice;
    }
    
}
?>

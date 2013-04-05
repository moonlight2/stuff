<?php

namespace Flash\Bundle\DefaultBundle\Repository;

interface GenericRepository
{
    /**
     * @param type $name - the name of the entity
     * @return boolean. true if it exists, false if it doesn't
     */
    public function exists($name);

    /**
     * @param type $name - name of the object to get
     * @return a populated object
     */
    public function get($name);
    
}
?>

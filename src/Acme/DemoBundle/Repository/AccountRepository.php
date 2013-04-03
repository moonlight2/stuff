<?php

namespace Acme\DemoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AccountRepository
 *
 */
class AccountRepository extends EntityRepository {

    public function loadUserByUserName($name) {
        return $this->getEntityManager()
                        ->createQuery('SELECT a FROM AcmeDemoBundle:Account a
                                       WHERE a.username = :name')
                        ->setParameter('name', $name)
                        ->getResult();
    }

}

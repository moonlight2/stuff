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

    public function findProduct($id) {

        $query = $this->getEntityManager()
                ->createQuery('SELECT a, p FROM AcmeDemoBundle:Account a
                                JOIN a.product p
                                WHERE a.id = :id')
                ->setParameter('id', $id);
            return $query->getSingleResult();

    }

}

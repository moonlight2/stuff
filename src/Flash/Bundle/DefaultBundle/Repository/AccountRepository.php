<?php

namespace Flash\Bundle\DefaultBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AccountRepository
 *
 */
class AccountRepository extends EntityRepository implements GenericRepository {

    public function exists($name) {
        $list = $this->getEntityManager()
                ->createQuery('SELECT a FROM FlashDefaultBundle:Account a
                                       WHERE a.username = :name')
                ->setParameter('name', $name)
                ->getResult();
        return sizeof($list) > 0;
    }

    public function get($name) {
        $list = $this->getEntityManager()
                ->createQuery('SELECT a FROM FlashDefaultBundle:Account a
                                       WHERE a.username = :name')
                ->setParameter('name', $name)
                ->getResult();
        return (sizeof($list) > 0) ? $list[0] : null;
    }

}

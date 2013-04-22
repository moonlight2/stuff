<?php

namespace Flash\Bundle\DefaultBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * RoleRepository
 *
 */
class RoleRepository extends EntityRepository implements GenericRepository {

    public function exists($name) {
        $list = $this->getEntityManager()
                ->createQuery('SELECT r FROM FlashDefaultBundle:Role r
                                       WHERE r.name = :name')
                ->setParameter('name', $name)
                ->getResult();
        return sizeof($list) > 0;
    }

    public function getByName($name) {
        $list = $this->getEntityManager()
                ->createQuery('SELECT r FROM FlashDefaultBundle:Role r
                                       WHERE r.name = :name')
                ->setParameter('name', $name)
                ->getResult();
        return (sizeof($list) > 0) ? $list[0] : null;
    }


}

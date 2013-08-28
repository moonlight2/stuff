<?php

namespace Flash\Bundle\DefaultBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AlbumRepository
 *
 */
class AlbumRepository extends EntityRepository {

    public function findAllByAccount($acc) {

        $list = $this->getEntityManager()
                ->createQuery('SELECT a FROM FlashDefaultBundle:Album a
                                       WHERE a.account = :account')
                ->setParameter('account', $acc)
                ->getResult();
        return (sizeof($list) > 0) ? $list : null;
    }

    public function exists($name, $acc) {

        $list = $this->getEntityManager()
                ->createQuery('SELECT a FROM FlashDefaultBundle:Album a
                                       WHERE a.name = :name AND a.account = :acc')
                ->setParameter('name', $name)
                ->setParameter('acc', $acc)
                ->getResult();
        return sizeof($list) > 0;
    }

    public function getByAccountAndName($acc, $name) {

        $list = $this->getEntityManager()
                ->createQuery('SELECT a FROM FlashDefaultBundle:Album a
                                       WHERE a.account = :acc
                                       AND a.name = :name')
                ->setParameter('acc', $acc)
                ->setParameter('name', $name)
                ->getResult();
        return (sizeof($list) > 0) ? $list[0] : null;
    }

}
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
}
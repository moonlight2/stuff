<?php

namespace Flash\Bundle\DefaultBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PhotoRepository
 *
 */
class PhotoRepository extends EntityRepository  {


    public function getByAccountAndPath($acc, $path) {
        $list = $this->getEntityManager()
                ->createQuery('SELECT p FROM FlashDefaultBundle:Photo p
                                       WHERE p.account = :account
                                       AND p.path = :path')
                ->setParameter('account', $acc)
                ->setParameter('path', $path)
                ->getResult();
        return (sizeof($list) > 0) ? $list[0] : null;
    }


}
<?php

namespace Flash\Bundle\DefaultBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PhotoRepository
 *
 */
class PhotoRepository extends EntityRepository {

    public function getByPath($path) {

        $list = $this->getEntityManager()
                ->createQuery('SELECT p FROM FlashDefaultBundle:Photo p
                                       WHERE p.path = :path')
                ->setParameter('path', $path)
                ->getResult();
        return (sizeof($list) > 0) ? $list[0] : null;
    }
    
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

    public function findByAccountAndId($acc, $id) {

        $list = $this->getEntityManager()
                ->createQuery('SELECT p FROM FlashDefaultBundle:Photo p
                                       WHERE p.id = :id')
                ->setParameter('id', $id)
                ->getResult();
        return (sizeof($list) > 0) ? $list[0] : null;
    }

    public function findAllByAccount($acc) {

        $list = $this->getEntityManager()
                ->createQuery('SELECT p FROM FlashDefaultBundle:Photo p
                                       WHERE p.account = :account AND p.avatar = 0')
                ->setParameter('account', $acc)
                ->getResult();
        return (sizeof($list) > 0) ? $list : null;
    }

    public function findAllByAccountAndAlbum($acc, $alb) {

        $list = $this->getEntityManager()
                ->createQuery('SELECT p FROM FlashDefaultBundle:Photo p
                                       WHERE p.account = :account
                                       AND p.album = :album 
                                       AND p.avatar = 0')
                ->setParameter('account', $acc)
                ->setParameter('album', $alb)
                ->getResult();
        return (sizeof($list) > 0) ? $list : null;
    }

    public function getAvatarByAccount($acc) {

        $list = $this->getEntityManager()
                ->createQuery('SELECT p FROM FlashDefaultBundle:Photo p
                                       WHERE p.account = :account AND p.avatar = 1')
                ->setParameter('account', $acc)
                ->getResult();
        return (sizeof($list) > 0) ? $list[0] : null;
    }

    public function getAvatarsByAccount($acc) {

        $list = $this->getEntityManager()
                ->createQuery('SELECT p FROM FlashDefaultBundle:Photo p
                                       WHERE p.account = :account AND p.avatar = 1')
                ->setParameter('account', $acc)
                ->getResult();
        return (sizeof($list) > 0) ? $list : null;
    }

    public function getLast($from, $to) {
        $list = $this->getEntityManager()
                ->createQuery("SELECT p FROM FlashDefaultBundle:Photo p ORDER BY p.id DESC")
                ->setFirstResult($from)
                ->setMaxResults($to)
                ->getResult();
        return (sizeof($list) > 0) ? $list : null;
    }

}
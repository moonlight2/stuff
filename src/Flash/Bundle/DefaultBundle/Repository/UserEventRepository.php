<?php

namespace Flash\Bundle\DefaultBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserEvent Repository
 *
 */
class UserEventRepository extends EntityRepository {

    public function findAllByUser($user, $from, $to) {

        $list = $this->getEntityManager()
                ->createQuery('SELECT e FROM FlashDefaultBundle:UserEvent e
                                       WHERE e.account = :account ORDER BY e.id DESC')
                ->setParameter('account', $user)
                ->setFirstResult($from)
                ->setMaxResults($to)
                ->getResult();
        return (sizeof($list) > 0) ? $list : null;
    }

    public function findAllByLimit($from, $to) {
        $list = $this->getEntityManager()
                ->createQuery('SELECT e FROM FlashDefaultBundle:UserEvent e')
                ->setFirstResult($from)
                ->setMaxResults($to)
                ->getResult();
        return (sizeof($list) > 0) ? $list : null;
    }

}

<?php

namespace Flash\Bundle\DefaultBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserEvent Repository
 *
 */
class UserEventRepository extends EntityRepository  {

    public function findAllByCurentUser($user) {

        $list = $this->getEntityManager()
                ->createQuery('SELECT e FROM FlashDefaultBundle:UserEvent e
                                       WHERE e.account = :account')
                ->setParameter('account', $user)
                ->getResult();
        return (sizeof($list) > 0) ? $list : null;
    }

}

<?php

namespace Flash\Bundle\DefaultBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EventRepository
 *
 */
class EventRepository extends EntityRepository  {


    public function getByGroup($group) {
        $list = $this->getEntityManager()
                ->createQuery("SELECT e FROM FlashDefaultBundle:Event e
                                WHERE e.group = :group ORDER BY e.id DESC")
                ->setParameter('group', $group)
                ->getResult();
        return (sizeof($list) > 0) ? $list : null;
    }

}
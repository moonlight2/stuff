<?php

namespace Flash\Bundle\DefaultBundle\Repository\Calendar;

use Doctrine\ORM\EntityRepository;

/**
 * CalendarEventRepository
 *
 */
class CalendarEventRepository extends EntityRepository  {


    public function findAllByAccount($acc) {
        
        $list = $this->getEntityManager()
                ->createQuery('SELECT e FROM FlashDefaultBundle:Calendar\CalendarEvent e
                                       WHERE e.account = :account')
                ->setParameter('account', $acc)
                ->getResult();
        return (sizeof($list) > 0) ? $list : null;
    }

}

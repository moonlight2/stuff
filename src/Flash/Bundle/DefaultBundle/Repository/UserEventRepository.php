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

    public function getLast($from, $to) {
        $list = $this->getEntityManager()
                ->createQuery("SELECT e FROM FlashDefaultBundle:UserEvent e ORDER BY e.id DESC")
                ->setFirstResult($from)
                ->setMaxResults($to)
                ->getResult();
        return (sizeof($list) > 0) ? $list : null;
    }

    public function getOwnTodaysEvents($acc, $type = null) {

        $sql = "SELECT id, account_id, title, description, edate, type
                FROM user_event
                WHERE account_id = '". $acc->getId() ."'
                AND DATE( edate ) >= DATE( NOW( ) )";
        if (null != $type) {
            $sql.= "AND type = '".$type."'";
        }
                
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        $list = $stmt->fetchAll();
        return (sizeof($list) > 0) ? $list : null;
    }

}

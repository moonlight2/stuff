<?php

namespace Flash\Bundle\DefaultBundle\Repository\Calendar;

use Doctrine\ORM\EntityRepository;


/**
 * CalendarEventRepository
 *
 */
class CalendarEventRepository extends EntityRepository {

    public function findAllByAccount($acc) {

        $list = $this->getEntityManager()
                ->createQuery('SELECT e FROM FlashDefaultBundle:Calendar\CalendarEvent e
                                       WHERE e.account = :account')
                ->setParameter('account', $acc)
                ->getResult();
        return (sizeof($list) > 0) ? $list : null;
    }

    public function confirm($acc_id, $id) {

        $sql = "UPDATE 
            account_calendarevent
            SET
            confirmed = '1'
            WHERE calendarevent_id = :id
            AND account_id = :acc_id";

        $params = array(
            "id" => $id,
            "acc_id" => $acc_id,
        );

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        return  $stmt->execute($params);
    }
    
    public function reject($acc_id, $id) {

        $sql = "UPDATE 
            account_calendarevent
            SET
            rejected = '1'
            WHERE calendarevent_id = :id
            AND account_id = :acc_id";

        $params = array(
            "id" => $id,
            "acc_id" => $acc_id,
        );

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        return  $stmt->execute($params);
    }
    
    public function watch($acc_id, $id) {

        $sql = "UPDATE 
            account_calendarevent
            SET
            is_watched = '1'
            WHERE calendarevent_id = :id
            AND account_id = :acc_id";

        $params = array(
            "id" => $id,
            "acc_id" => $acc_id,
        );

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        return  $stmt->execute($params);
    }
    
    public function setPercent($acc_id, $id, $percent) {

        $sql = "UPDATE 
            account_calendarevent
            SET
            percent = :percent
            WHERE calendarevent_id = :id
            AND account_id = :acc_id";

        $params = array(
            "id" => $id,
            "acc_id" => $acc_id,
            "percent" => $percent,
        );

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        return  $stmt->execute($params);
    }

    public function getAllConfirmed($id) {

        $sql = "SELECT 
            account_id, 
            calendarevent_id, 
            confirmed, 
            rejected,
            percent
            from account_calendarevent
            WHERE calendarevent_id = :id";

        $params = array(
            "id" => $id,
        );

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);
        $list = $stmt->fetchAll();
        return (sizeof($list) > 0) ? $list : null;
    }
    
    public function getAllByYearAndMonth($acc_id, $year, $month) {

        $sql = "select 
            ce.id, 
            ce.title, 
            ce.text, 
            ce.start, 
            ce.end  
            from account_calendarevent as ac
                join calendar_events as ce
                on ce.id = ac.calendarevent_id 
                join account as a 
                on a.id = ac.account_id 
            and a.id = :acc_id 
            and ce.start like '".$year."-".$month."%'";

        $params = array(
            "acc_id" => $acc_id,
        );

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);
        $list = $stmt->fetchAll();
        return (sizeof($list) > 0) ? $list : null;
    }
    
    public function getSingleEventStatus($id) {

        $sql = "SELECT 
            ac.confirmed,
            ac.rejected,
            ac.account_id, 
            ac.calendarevent_id, 
            ac.percent,
            a.first_name, 
            a.last_name
            from account_calendarevent as ac, 
            account as a
            WHERE ac.account_id = a.id
            AND ac.calendarevent_id = :id";
        
        $params = array(
            "id" => $id
        );

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);
        $list = $stmt->fetchAll();
        return (sizeof($list) > 0) ? $list : null;
    }
    public function getEventsStatus($acc_id, $eventStr) {

        $sql = "SELECT 
            confirmed,
            rejected,
            account_id, 
            calendarevent_id
            from account_calendarevent
            WHERE account_id = :acc_id and calendarevent_id in (".$eventStr.")";
        
        $params = array(
            "acc_id" => $acc_id
        );

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);
        $list = $stmt->fetchAll();
        return (sizeof($list) > 0) ? $list : null;
    }

}

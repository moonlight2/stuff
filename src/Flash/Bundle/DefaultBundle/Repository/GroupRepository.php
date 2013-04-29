<?php

namespace Flash\Bundle\DefaultBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AccountRepository
 *
 */
class GroupRepository extends EntityRepository implements GenericRepository {

    public function exists($name) {
        $list = $this->getEntityManager()
                ->createQuery('SELECT a FROM FlashDefaultBundle:Account a
                                       WHERE a.username = :name')
                ->setParameter('name', $name)
                ->getResult();
        return sizeof($list) > 0;
    }

    public function getByName($name) {
        $list = $this->getEntityManager()
                ->createQuery('SELECT a FROM FlashDefaultBundle:Account a
                                       WHERE a.username = :name')
                ->setParameter('name', $name)
                ->getResult();
        return (sizeof($list) > 0) ? $list[0] : null;
    }

    public function getByLocation($country_id, $city_id) {
        $list = $this->getEntityManager()
                ->createQuery("SELECT g FROM FlashDefaultBundle:Group g
                                WHERE g.country = :country_id AND g.city = :city_id")
                ->setParameter('country_id', $country_id)
                ->setParameter('city_id', $city_id)
                ->getResult();
        return (sizeof($list) > 0) ? $list : null;
    }

}

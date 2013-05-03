<?php

namespace Flash\Bundle\DefaultBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AccountRepository
 *
 */
class AccountRepository extends EntityRepository implements GenericRepository {

    public function exists($name) {
        $list = $this->getEntityManager()
                ->createQuery('SELECT a FROM FlashDefaultBundle:Account a
                                       WHERE a.username = :name')
                ->setParameter('name', $name)
                ->getResult();
        return sizeof($list) > 0;
    }
    
    public function existsEmail($email) {
        $list = $this->getEntityManager()
                ->createQuery('SELECT a FROM FlashDefaultBundle:Account a
                                       WHERE a.email = :email')
                ->setParameter('email', $email)
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

    public function getByRole($role) {
        $list = $this->getEntityManager()
                ->createQuery("SELECT a FROM FlashDefaultBundle:Account a
                                JOIN a.roles r WHERE r.name = :role")
                ->setParameter('role', $role)
                ->getResult();
        return (sizeof($list) > 0) ? $list : null;
    }
    
    public function getByEmail($email) {
        $list = $this->getEntityManager()
                ->createQuery('SELECT a FROM FlashDefaultBundle:Account a
                                       WHERE a.email = :email')
                ->setParameter('email', $email)
                ->getResult();
        return (sizeof($list) > 0) ? $list[0] : null;
    }

}

<?php

namespace Flash\Bundle\DefaultBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AccountRepository
 *
 */
class AccountRepository extends EntityRepository implements GenericRepository, \Symfony\Component\Security\Core\User\UserProviderInterface {

    public function exists($name) {
        $list = $this->getEntityManager()
                ->createQuery('SELECT a FROM FlashDefaultBundle:Account a
                                       WHERE a.username = :name')
                ->setParameter('name', $name)
                ->getResult();
        return sizeof($list) > 0;
    }

    public function getExtendedInfo($id) {
        $sql = "SELECT 
            id,
            email, 
            first_name,
            last_name,
            city_id, 
            country_id 
        from account WHERE id = :acc_id LIMIT 1";

        $params = array(
            "acc_id" => $id,
        );

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);
        $list = $stmt->fetchAll();
        return (sizeof($list) > 0) ? $list[0] : null;
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
    

    public function getLeaders() {
        $list = $this->getEntityManager()
                ->createQuery("SELECT a FROM FlashDefaultBundle:Account a
                                WHERE a.isLeader = :isLeader")
                ->setParameter('isLeader', true)
                ->getResult();
        return (sizeof($list) > 0) ? $list : null;
    }
    
    public function getLeadersByLocalion($country_id, $city_id) {
        $list = $this->getEntityManager()
                ->createQuery("SELECT a FROM FlashDefaultBundle:Account a
                                WHERE a.isLeader = :isLeader
                                AND a.country = :country_id
                                AND a.city = :city_id")
                ->setParameter('isLeader', true)
                ->setParameter('country_id', $country_id)
                ->setParameter('city_id', $city_id)
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

    public function loadUserByUsername($username) {
        return $this->getEntityManager()
                        ->createQuery('SELECT a FROM FlashDefaultBundle:Account a
         WHERE a.username = :username
         OR a.email = :username')
                        ->setParameters(array(
                            'username' => $username
                        ))
                        ->getOneOrNullResult();
    }

    public function refreshUser(\Symfony\Component\Security\Core\User\UserInterface $user) {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class) {
        return $class === 'Flash/Bundle/DefaultBundle/Entity/Account';
    }

}

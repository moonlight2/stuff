<?php

namespace Flash\Bundle\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Photo
 *
 * @ORM\Table(name="custom_role")
 * @ORM\Entity
 */
class CustomRole implements \Symfony\Component\Security\Core\Role\RoleInterface {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     *
     * @ManyToOne(targetEntity="Account", inversedBy="customRoles")
     */
    private $account;
    
    
    public function __construct(\Symfony\Component\Security\Core\User\UserInterface $acc) {
        $this->account = $acc;
        $this->name = 'ROLE_LEADER_ID_' . strtoupper($this->account->getGroup()->getId());
    }

    public function getRole() {
        return $this->name;
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CustomRole
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set account
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Account $account
     * @return CustomRole
     */
    public function setAccount(\Flash\Bundle\DefaultBundle\Entity\Account $account = null)
    {
        $this->account = $account;
    
        return $this;
    }

    /**
     * Get account
     *
     * @return \Flash\Bundle\DefaultBundle\Entity\Account 
     */
    public function getAccount()
    {
        return $this->account;
    }
}
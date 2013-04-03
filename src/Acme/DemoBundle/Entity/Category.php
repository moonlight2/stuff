<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Category {

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
     * @ORM\OneToMany(targetEntity="Account", mappedBy="category")
     */
    private $accounts;

    public function __construct() {
        $this->accounts = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }


    /**
     * Add accounts
     *
     * @param \Acme\DemoBundle\Entity\Acount $accounts
     * @return Category
     */
    public function addAccount(\Acme\DemoBundle\Entity\Acount $accounts)
    {
        $this->accounts[] = $accounts;
    
        return $this;
    }

    /**
     * Remove accounts
     *
     * @param \Acme\DemoBundle\Entity\Acount $accounts
     */
    public function removeAccount(\Acme\DemoBundle\Entity\Acount $accounts)
    {
        $this->accounts->removeElement($accounts);
    }

    /**
     * Get accounts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAccounts()
    {
        return $this->accounts;
    }
}
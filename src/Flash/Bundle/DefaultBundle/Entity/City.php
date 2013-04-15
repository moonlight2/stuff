<?php

namespace Flash\Bundle\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * City
 *
 * @ORM\Table(name="city")
 * @ORM\Entity
 */
class City {

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
     * @OneToMany(targetEntity="Group", mappedBy="city")
     */
    private $groups;

    /**
     *
     * @ManyToOne(targetEntity="Country", inversedBy="cities")
     */
    private $country;

    public function __construct() {
        $this->groups = new ArrayCollection();
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
     * Add groups
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Group $groups
     * @return City
     */
    public function addGroup(\Flash\Bundle\DefaultBundle\Entity\Group $groups) {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Group $groups
     */
    public function removeGroup(\Flash\Bundle\DefaultBundle\Entity\Group $groups) {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups() {
        return $this->groups;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return City
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
     * Set country
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Country $country
     * @return City
     */
    public function setCountry(\Flash\Bundle\DefaultBundle\Entity\Country $country = null)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return \Flash\Bundle\DefaultBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }
}
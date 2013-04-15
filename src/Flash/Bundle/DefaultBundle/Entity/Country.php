<?php

namespace Flash\Bundle\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Role
 *
 * @ORM\Table(name="country")
 * @ORM\Entity
 */
class Country {

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
     * @OneToMany(targetEntity="City", mappedBy="country")
     */
    private $cities;

    public function __construct($name) {
        $this->name = $name;
        $this->cities = new ArrayCollection();
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
     * @return Country
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
     * Add cities
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\City $city
     * @return Country
     */
    public function addCity(\Flash\Bundle\DefaultBundle\Entity\City $city)
    {
        $this->cities[] = $city;
    
        return $this;
    }

    /**
     * Remove cities
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\City $city
     */
    public function removeCity(\Flash\Bundle\DefaultBundle\Entity\City $city)
    {
        $this->cities->removeElement($city);
    }

    /**
     * Get cities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCities()
    {
        return $this->cities;
    }
}
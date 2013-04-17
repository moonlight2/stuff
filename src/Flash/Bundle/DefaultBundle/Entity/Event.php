<?php

namespace Flash\Bundle\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Photo
 *
 * @ORM\Table(name="event")
 * @ORM\Entity
 */
class Event {

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
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     *
     * @ManyToOne(targetEntity="City", inversedBy="events")
     */
    private $city;
    
    /**
     *
     * @ManyToOne(targetEntity="Group", inversedBy="events")
     */
    private $group;


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
     * @return Event
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
     * Set description
     *
     * @param string $description
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set city
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\City $city
     * @return Event
     */
    public function setCity(\Flash\Bundle\DefaultBundle\Entity\City $city = null)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return \Flash\Bundle\DefaultBundle\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set group
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Group $group
     * @return Event
     */
    public function setGroup(\Flash\Bundle\DefaultBundle\Entity\Group $group = null)
    {
        $this->group = $group;
    
        return $this;
    }

    /**
     * Get group
     *
     * @return \Flash\Bundle\DefaultBundle\Entity\Group 
     */
    public function getGroup()
    {
        return $this->group;
    }
}
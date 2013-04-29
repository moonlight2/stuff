<?php

namespace Flash\Bundle\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Flash\Bundle\DefaultBundle\Entity\Account;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Group
 *
 * @ORM\Table(name="mygroup")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Flash\Bundle\DefaultBundle\Repository\GroupRepository")
 * @ExclusionPolicy("all")
 */
class Group {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Expose
     */
    private $name;

    /**
     * @OneToMany(targetEntity="Account", mappedBy="group")
     */
    private $accounts;

    /**
     * @OneToMany(targetEntity="Event", mappedBy="group")
     * @Expose
     */
    private $events;

    /**
     * @ORM\Column(name="rating", type="integer", nullable=true)
     * @Expose
     */
    private $rating;

    /**
     * @var date
     *
     * @ORM\Column(name="registered", type="datetime")
     * @Expose
     */
    private $dateRegist;

    /**
     * @ORM\Column(name="city_id", type="integer")
     * @Expose
     */
    private $city;

    /**
     * @ORM\Column(name="country_id", type="integer")
     * @Expose
     */
    private $country;

    public function __construct() {

        $this->accounts = new ArrayCollection();
        $this->events = new ArrayCollection();
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
     * @return Group
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
     * @param \Flash\Bundle\DefaultBundle\Entity\Account $accounts
     * @return Group
     */
    public function addAccount(\Flash\Bundle\DefaultBundle\Entity\Account $accounts) {
        $this->accounts[] = $accounts;

        return $this;
    }

    /**
     * Remove accounts
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Account $accounts
     */
    public function removeAccount(\Flash\Bundle\DefaultBundle\Entity\Account $accounts) {
        $this->accounts->removeElement($accounts);
    }

    /**
     * Get accounts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAccounts() {
        return $this->accounts;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     * @return Group
     */
    public function setRating($rating) {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating() {
        return $this->rating;
    }

    /**
     * Add events
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Event $events
     * @return Group
     */
    public function addEvent(\Flash\Bundle\DefaultBundle\Entity\Event $events) {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Event $events
     */
    public function removeEvent(\Flash\Bundle\DefaultBundle\Entity\Event $events) {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvents() {
        return $this->events;
    }

    /**
     * Set city
     *
     * @param integer $city
     * @return Group
     */
    public function setCity($city) {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return integer 
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param integer $country
     * @return Group
     */
    public function setCountry($country) {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return integer 
     */
    public function getCountry() {
        return $this->country;
    }


    /**
     * Set dateRegist
     *
     * @param \DateTime $dateRegist
     * @return Group
     */
    public function setDateRegist($dateRegist)
    {
        $this->dateRegist = $dateRegist;
    
        return $this;
    }

    /**
     * Get dateRegist
     *
     * @return \DateTime 
     */
    public function getDateRegist()
    {
        return $this->dateRegist;
    }
}
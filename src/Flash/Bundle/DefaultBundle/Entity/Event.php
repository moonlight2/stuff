<?php

namespace Flash\Bundle\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Photo
 *
 * @ORM\Table(name="event")
 * @ORM\Entity
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="Flash\Bundle\DefaultBundle\Repository\EventRepository")
 */
class Event {

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
     * @Assert\NotBlank(message = "Поле name не может быть пустым")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Expose
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="text", nullable=true)
     * @Expose
     */
    private $type;

    /**
     * @var date
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime(message = "Неверные данные date")
     * @Expose
     */
    private $date;

    /**
     * @ORM\Column(name="city_id", type="integer")
     * @Assert\NotBlank(message = "Поле city не может быть пустым")
     * @Expose
     */
    private $city;

    /**
     * @ORM\Column(name="country_id", type="integer")
     * @Expose
     * @Assert\NotBlank(message = "Поле country не может быть пустым")
     */
    private $country;

    /**
     *
     * @ManyToOne(targetEntity="Group", inversedBy="events")
     * @Expose
     */
    private $group;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_confirmed", type="boolean")
     * @Expose
     */
    protected $isConfirmed = false;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_shared", type="boolean")
     * @Expose
     */
    protected $isShared = false;

    /**
     * @ManyToOne(targetEntity="\Flash\Bundle\DefaultBundle\Entity\Account", inversedBy="calendarEvents")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="CASCADE")
     * @Expose
     */
    protected $account;
    
    public function __construct(\Symfony\Component\Security\Core\User\UserInterface $account) {
        $this->account = $account;
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
     * @return Event
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
     * Set description
     *
     * @param string $description
     * @return Event
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set group
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Group $group
     * @return Event
     */
    public function setGroup(\Flash\Bundle\DefaultBundle\Entity\Group $group = null) {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \Flash\Bundle\DefaultBundle\Entity\Group 
     */
    public function getGroup() {
        return $this->group;
    }

    /**
     * Set city
     *
     * @param integer $city
     * @return Event
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
     * @return Event
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

    public function setUpdated() {
        // will NOT be saved in the database
        $this->updated->modify("now");
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Event
     */
    public function setDate(\DateTime $date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Event
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set isConfirmed
     *
     * @param boolean $isConfirmed
     * @return Event
     */
    public function setIsConfirmed($isConfirmed) {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    /**
     * Get isConfirmed
     *
     * @return boolean 
     */
    public function getIsConfirmed() {
        return $this->isConfirmed;
    }


    /**
     * Set account
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Account $account
     * @return Event
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

    /**
     * Set isShared
     *
     * @param boolean $isShared
     * @return Event
     */
    public function setIsShared($isShared)
    {
        $this->isShared = $isShared;
    
        return $this;
    }

    /**
     * Get isShared
     *
     * @return boolean 
     */
    public function getIsShared()
    {
        return $this->isShared;
    }
}
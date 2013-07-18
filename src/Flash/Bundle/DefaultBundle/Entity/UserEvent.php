<?php

namespace Flash\Bundle\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Photo
 *
 * @ORM\Table(name="user_event")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Flash\Bundle\DefaultBundle\Repository\UserEventRepository")
 * @ExclusionPolicy("all")
 */
class UserEvent {

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
     * @ORM\Column(name="title", type="string", length=255)
     * @Expose
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Expose
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     * @Expose
     */
    private $type;

    /**
     * @var date
     *
     * @ORM\Column(name="edate", type="datetime")
     * @Expose
     */
    private $edate;
    private $date;

    /**
     *
     * @ManyToOne(targetEntity="Account", inversedBy="userEvents")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $account;

    public function __construct() {
        $this->setEdate(new \DateTime("now"));
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
     * Set title
     *
     * @param string $title
     * @return UserEvent
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return UserEvent
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }
    
    /**
     * Add new part to description
     *
     * @param string $description
     * @return UserEvent
     */
    public function addToDescription($description) {
        $this->description.= $description;

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
     * Set date
     *
     * @param \DateTime $date
     * @return UserEvent
     */
    public function setEdate($date) {
        $this->edate = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getEdate() {
        return $this->edate;
    }

    /**
     * Set account
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Account $account
     * @return UserEvent
     */
    public function setAccount(\Flash\Bundle\DefaultBundle\Entity\Account $account = null) {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \Flash\Bundle\DefaultBundle\Entity\Account 
     */
    public function getAccount() {
        return $this->account;
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

}
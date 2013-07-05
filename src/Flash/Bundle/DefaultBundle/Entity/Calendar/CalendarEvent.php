<?php

namespace Flash\Bundle\DefaultBundle\Entity\Calendar;

use Flash\Bundle\DefaultBundle\Entity\Common\Estimable;
use Doctrine\ORM\Mapping as ORM;
use Flash\Bundle\DefaultBundle\Entity\Account;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

/**
 * CalendarEvent
 *
 * @ORM\Table(name="calendar_events")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Flash\Bundle\DefaultBundle\Repository\Calendar\CalendarEventRepository")
 * @ExclusionPolicy("all")
 */
class CalendarEvent {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;

    /**
     * @var string
     * @Type("string")
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     * @Expose
     */
    protected $title;

    /**
     * @var string
     * @Type("string")
     * @ORM\Column(name="color", type="string", length=255, nullable=true)
     * @Expose
     */
    protected $color;

    /**
     * @ORM\Column(type="text")
     * @Expose
     */
    protected $text;

    /**
     * @var boolean
     *
     * @SerializedName("allDay")
     * @Expose
     * @ORM\Column(name="all_day", type="boolean")
     */
    protected $allDay;

    /**
     * @var boolean
     * 
     * @SerializedName("isShown")
     * @ORM\Column(name="is_shown", type="boolean")
     * @Expose
     */
    protected $isShown = false;

    /**
     * @var boolean
     * 
     * @SerializedName("isShared")
     * @ORM\Column(name="is_shared", type="boolean")
     * @Expose
     */
    protected $isShared = false;

    /**
     * @var date
     *
     * @ORM\Column(name="start", type="datetime")
     * @Expose
     */
    protected $start;

    /**
     * @var date
     *
     * @ORM\Column(name="end", type="datetime")
     * @Expose
     */
    protected $end;

    /**
     * @var account
     *
     * @Expose
     */
    protected $account;



    public function equals(\Flash\Bundle\DefaultBundle\Entity\Calendar\CalendarEvent $event) {

        return $event->getId() == $this->getId();
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
     * @return CalendarEvent
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
     * Set text
     *
     * @param string $text
     * @return CalendarEvent
     */
    public function setText($text) {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText() {
        return $this->text;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     * @return CalendarEvent
     */
    public function setDateStart($dateStart) {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime 
     */
    public function getDateStart() {
        return $this->dateStart;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     * @return CalendarEvent
     */
    public function setDateEnd($dateEnd) {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime 
     */
    public function getDateEnd() {
        return $this->dateEnd;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     * @return CalendarEvent
     */
    public function setStart($start) {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime 
     */
    public function getStart() {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return CalendarEvent
     */
    public function setEnd($end) {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime 
     */
    public function getEnd() {
        return $this->end;
    }

    /**
     * Set allDay
     *
     * @param boolean $allDay
     * @return CalendarEvent
     */
    public function setAllDay($allDay) {
        $this->allDay = $allDay;

        return $this;
    }

    /**
     * Get allDay
     *
     * @return boolean 
     */
    public function getAllDay() {
        return $this->allDay;
    }

    /**
     * Set isShown
     *
     * @param boolean $isShown
     * @return CalendarEvent
     */
    public function setIsShown($isShown) {
        $this->isShown = $isShown;

        return $this;
    }

    /**
     * Get isShown
     *
     * @return boolean 
     */
    public function getIsShown() {
        return $this->isShown;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return CalendarEvent
     */
    public function setColor($color) {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * Set isShared
     *
     * @param boolean $isShared
     * @return CalendarEvent
     */
    public function setIsShared($isShared) {
        $this->isShared = $isShared;

        return $this;
    }

    /**
     * Get isShared
     *
     * @return boolean 
     */
    public function getIsShared() {
        return $this->isShared;
    }


    /**
     * Add confirmed
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Account $confirmed
     * @return CalendarEvent
     */
    public function addConfirmed(\Flash\Bundle\DefaultBundle\Entity\Account $confirmed)
    {
        $this->confirmed[] = $confirmed;
    
        return $this;
    }

    /**
     * Remove confirmed
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Account $confirmed
     */
    public function removeConfirmed(\Flash\Bundle\DefaultBundle\Entity\Account $confirmed)
    {
        $this->confirmed->removeElement($confirmed);
    }

    /**
     * Get confirmed
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Add rejected
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Account $rejected
     * @return CalendarEvent
     */
    public function addRejected(\Flash\Bundle\DefaultBundle\Entity\Account $rejected)
    {
        $this->rejected[] = $rejected;
    
        return $this;
    }

    /**
     * Remove rejected
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Account $rejected
     */
    public function removeRejected(\Flash\Bundle\DefaultBundle\Entity\Account $rejected)
    {
        $this->rejected->removeElement($rejected);
    }

    /**
     * Get rejected
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRejected()
    {
        return $this->rejected;
    }
}
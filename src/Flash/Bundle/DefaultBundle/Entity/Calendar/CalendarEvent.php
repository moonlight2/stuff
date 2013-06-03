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
use JMS\Serializer\Annotation\Type;

/**
 * Group
 *
 * @ORM\Table(name="calendar_events")
 * @ORM\Entity
 * @ExclusionPolicy("all")
 */
class CalendarEvent  {

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
     * @ORM\Column(type="text")
     * @Expose
     */
    protected $text;

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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return CalendarEvent
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return CalendarEvent
     */
    public function setText($text)
    {
        $this->text = $text;
    
        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     * @return CalendarEvent
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
    
        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime 
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     * @return CalendarEvent
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;
    
        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime 
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set account
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Account $account
     * @return CalendarEvent
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
     * Set start
     *
     * @param \DateTime $start
     * @return CalendarEvent
     */
    public function setStart($start)
    {
        $this->start = $start;
    
        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return CalendarEvent
     */
    public function setEnd($end)
    {
        $this->end = $end;
    
        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime 
     */
    public function getEnd()
    {
        return $this->end;
    }
}
<?php
namespace Flash\Bundle\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Flash\Bundle\DefaultBundle\Entity\Account;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Group
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity
 * @ExclusionPolicy("all")
 */
class Comment {
    
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
     * @ORM\Column(name="rating", type="integer", nullable=true)
     * @Expose
     */
    protected $rating;

    /**
     * @var date
     *
     * @ORM\Column(name="posted", type="datetime")
     * @Expose
     */
    protected $datePost;

    /**
     * @ManyToOne(targetEntity="Account", inversedBy="comments")
     */
    protected $account;
    
    /**
     * @ManyToOne(targetEntity="Photo", inversedBy="comments")
     */
    protected $photo;

    public function __construct() {
        $this->setDatePost(new \DateTime("now"));
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
     * Set rating
     *
     * @param integer $rating
     * @return Comment
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    
        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set datePost
     *
     * @param \DateTime $datePost
     * @return Comment
     */
    public function setDatePost(\DateTime $datePost)
    {
        $this->datePost = $datePost;
    
        return $this;
    }

    /**
     * Get datePost
     *
     * @return \DateTime 
     */
    public function getDatePost()
    {
        return $this->datePost;
    }

    /**
     * Set account
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Account $account
     * @return Comment
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
     * Set photo
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Photo $photo
     * @return Comment
     */
    public function setPhoto(\Flash\Bundle\DefaultBundle\Entity\Photo $photo = null)
    {
        $this->photo = $photo;
    
        return $this;
    }

    /**
     * Get photo
     *
     * @return \Flash\Bundle\DefaultBundle\Entity\Photo 
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}
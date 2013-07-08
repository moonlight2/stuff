<?php

namespace Flash\Bundle\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Group
 *
 * @ORM\Table(name="photo_album")
 * @ORM\Entity
 * @ExclusionPolicy("all")
 */
class PhotoAlbum {
    
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
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(message = "Поле name не может быть пустым")
     * @Expose
     */
    protected $name;


    /**
     * @OneToMany(targetEntity="Photo", mappedBy="photoAlbum")
     */
    protected $photos;

    /**
     * @var date
     *
     * @ORM\Column(name="registered", type="datetime")
     * @Expose
     */
    protected $dateRegist;



    public function __construct($name) {
        $this->name = $name;
        $this->setDateRegist(new \DateTime("now"));
        $this->photos = new ArrayCollection();
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
     * @return PhotoAlbum
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
     * Set dateRegist
     *
     * @param \DateTime $dateRegist
     * @return PhotoAlbum
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

    /**
     * Add photos
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Photo $photos
     * @return PhotoAlbum
     */
    public function addPhoto(\Flash\Bundle\DefaultBundle\Entity\Photo $photos)
    {
        $this->photos[] = $photos;
    
        return $this;
    }

    /**
     * Remove photos
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Photo $photos
     */
    public function removePhoto(\Flash\Bundle\DefaultBundle\Entity\Photo $photos)
    {
        $this->photos->removeElement($photos);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhotos()
    {
        return $this->photos;
    }
}
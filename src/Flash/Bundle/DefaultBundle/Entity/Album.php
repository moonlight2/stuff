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
 * @ORM\Table(name="album")
 * @ORM\Entity
 * @ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks
 */
class Album {

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
     * @OneToMany(targetEntity="Photo", mappedBy="album")
     */
    protected $photos;

    /**
     *
     * @ManyToOne(targetEntity="Account", inversedBy="photoAlbums")
     */
    protected $account;

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
     * @ORM\PrePersist()
     */
    public function createAlbum() {

        if (!file_exists($this->getUploadRootDir())) {
            mkdir($this->getUploadRootDir());
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {

        rmdir($this->getUploadRootDir());
    }

    public function getUploadRootDir() {
        // the absolute directory path where uploaded
        // documents should be saved

        return __DIR__ . '/../../../../uploads/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'photos/' . $this->getAccount()->getId() . "/" . $this->getName();
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
     * @return PhotoAlbum
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
     * Set dateRegist
     *
     * @param \DateTime $dateRegist
     * @return PhotoAlbum
     */
    public function setDateRegist($dateRegist) {
        $this->dateRegist = $dateRegist;

        return $this;
    }

    /**
     * Get dateRegist
     *
     * @return \DateTime 
     */
    public function getDateRegist() {
        return $this->dateRegist;
    }

    /**
     * Add photos
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Photo $photos
     * @return PhotoAlbum
     */
    public function addPhoto(\Flash\Bundle\DefaultBundle\Entity\Photo $photos) {
        $this->photos[] = $photos;

        return $this;
    }

    /**
     * Remove photos
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Photo $photos
     */
    public function removePhoto(\Flash\Bundle\DefaultBundle\Entity\Photo $photos) {
        $this->photos->removeElement($photos);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhotos() {
        return $this->photos;
    }

    /**
     * Set account
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Account $account
     * @return Photo
     */
    public function setAccount(\Symfony\Component\Security\Core\User\UserInterface $account = null) {
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

}
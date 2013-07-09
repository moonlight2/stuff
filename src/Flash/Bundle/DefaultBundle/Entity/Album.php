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

        $this->recursive_remove_directory($this->getUploadRootDir());
        //recursive_remove_directory($this->getUploadRootDir());
    }

    private function recursive_remove_directory($directory, $empty = FALSE) {
        // if the path has a slash at the end we remove it here
        if (substr($directory, -1) == '/') {
            $directory = substr($directory, 0, -1);
        }

        // if the path is not valid or is not a directory ...
        if (!file_exists($directory) || !is_dir($directory)) {
            // ... we return false and exit the function
            return FALSE;

            // ... if the path is not readable
        } elseif (!is_readable($directory)) {
            // ... we return false and exit the function
            return FALSE;

            // ... else if the path is readable
        } else {

            // we open the directory
            $handle = opendir($directory);

            // and scan through the items inside
            while (FALSE !== ($item = readdir($handle))) {
                // if the filepointer is not the current directory
                // or the parent directory
                if ($item != '.' && $item != '..') {
                    // we build the new path to delete
                    $path = $directory . '/' . $item;

                    // if the new path is a directory
                    if (is_dir($path)) {
                        // we call this function with the new path
                        $this->recursive_remove_directory($path);

                        // if the new path is a file
                    } else {
                        // we remove the file
                        unlink($path);
                    }
                }
            }
            // close the directory
            closedir($handle);

            // if the option to empty is not set to true
            if ($empty == FALSE) {
                // try to delete the now empty directory
                if (!rmdir($directory)) {
                    // return false if not possible
                    return FALSE;
                }
            }
            // return success
            return TRUE;
        }
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
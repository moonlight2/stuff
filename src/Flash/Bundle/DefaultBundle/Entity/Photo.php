<?php

namespace Flash\Bundle\DefaultBundle\Entity;

use Flash\Bundle\DefaultBundle\Entity\Common\Estimable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Photo
 *
 * @ORM\Table(name="photo")
 * @ORM\Entity
 * @ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Flash\Bundle\DefaultBundle\Repository\PhotoRepository")
 */
class Photo {

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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Expose
     */
    protected $name;

    /**
     * @Assert\File(maxSize="2000000")
     */
    protected $file;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     * @Assert\NotBlank
     * @Expose
     */
    protected $path;

    /**
     * @Expose
     * @OneToMany(targetEntity="\Flash\Bundle\DefaultBundle\Entity\Comment\PhotoComment", mappedBy="photo")
     */
    protected $comments;

    /**
     *
     * @ManyToOne(targetEntity="Account", inversedBy="photos")
     */
    protected $account;

    /**
     *
     * @ManyToOne(targetEntity="Album", inversedBy="photos")
     */
    protected $album;
    protected $photoAlbum;

    /**
     * @var boolean
     *
     * @ORM\Column(name="avatar", type="boolean")
     * @Expose
     */
    protected $avatar = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="garbage", type="boolean")
     * @Expose
     */
    protected $garbage = false;

    /**
     * Bidirectional - Many comments are favorited by many users (INVERSE SIDE)
     *
     * @ORM\ManyToMany(targetEntity="Account", mappedBy="photoLike")
     * @Expose
     */
    protected $rating;

    /**
     * Constructor
     */
    public function __construct() {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rating = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get rating count
     * 
     * @return int
     */
    public function getRatingCount() {
        return $this->rating->count();
    }

    /**
     * Check rating for exists
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface $rating
     */
    public function existsRating(\Symfony\Component\Security\Core\User\UserInterface $rating) {

        $accs = $this->rating->getValues();
        foreach ($accs as $acc) {
            if ($acc->getEmail() == $rating->getEmail()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Remove rating
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface $rating
     */
    public function removeRating(\Symfony\Component\Security\Core\User\UserInterface $rating) {
        $this->rating->removeElement($rating);
    }

    public function addRating(\Symfony\Component\Security\Core\User\UserInterface $rating) {
        $this->rating[] = $rating;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {

        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename . '.' . $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {

        if (null === $this->getFile()) {
            return;
        }

        if ($this->isGarbage()) {
            $this->createAlbum('garbage');
            $this->getFile()->move($this->getUploadRootDir() . "/garbage", $this->path);
        } else {
            $this->getFile()->move($this->getUploadRootDir(), $this->path);
        }

        if (false == $this->isAvatar() && true !== $this->isGarbage()) {
            $this->createThumbnail();
        }

        if (false != $this->isAvatar()) {
            $this->createAvatar();
            $this->removePhotos();
        }
        $this->file = null;
    }

    public function createThumbnail() {

        $this->createAlbum('thumb');

        $image = new \Flash\Bundle\DefaultBundle\Lib\SimpleImage();
        $image->load($this->getAbsolutePath());
        $image->resizeToWidth(150);
        $image->save($this->getAbsoluteAlbumPath('thumb'));
    }

    public function createAlbum($aName) {

        if (!file_exists($this->getUploadRootDir() . '/' . $aName)) {
            mkdir($this->getUploadRootDir() . '/' . $aName);
        }
    }

    public function deleteAlbum($aName) {

        if (!file_exists($this->getUploadRootDir() . '/' . $aName)) {
            mkdir($this->getUploadRootDir() . '/' . $aName);
        }
    }

    public function createAvatar() {

        $this->createAlbum('avatar');

        $image = new \Flash\Bundle\DefaultBundle\Lib\SimpleImage();
        $image->load($this->getAbsolutePath());
        $image->resizeToWidth(150);
        $image->save($this->getAbsoluteAlbumPath('avatar'));
    }

    public function moveToGarbage() {

        $this->createAlbum('garbage');

        $image = new \Flash\Bundle\DefaultBundle\Lib\SimpleImage();
        $image->load($this->getAbsolutePath());
        $image->resizeToWidth(150);
        $image->save($this->getAbsoluteAlbumPath('garbage'));
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {

        if ($this->isAvatar() == TRUE) {
            if ($file = $this->getAbsoluteAlbumPath('avatar')) {
                unlink($file);
            }
        } else {
            if (NULL != $this->getAbsolutePath()) {
                unlink($this->getAbsolutePath());
            }
            if (NULL != $this->getAbsoluteAlbumPath('thumb')) {
                unlink($this->getAbsoluteAlbumPath('thumb'));
            }
        }
    }

    public function removePhotos() {
        if (NULL != $this->getAbsolutePath()) {
            unlink($this->getAbsolutePath());
        }
        //if (NULL != $this->getAbsoluteAlbumPath('thumb')) {
        //    unlink($this->getAbsoluteAlbumPath('thumb'));
        //}
    }

    public function getAbsolutePath() {
        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->path;
    }

    public function getAbsoluteAlbumPath($aName) {
        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $aName . '/' . $this->path;
    }

    public function getWebPath() {
        return null === $this->path ? null : 'image' . '/' . $this->getAccount()->getId() . '/' . $this->path;
    }

    public function getUploadRootDir() {
        // the absolute directory path where uploaded
        // documents should be saved

        return __DIR__ . '/../../../../uploads/' . $this->getUploadDir();
    }

    protected function getUploadDir() {

        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.t
        if ($this->isAvatar() || $this->isGarbage()) {
            return 'photos/' . $this->getAccount()->getId();
        } else {
            return 'photos/' . $this->getAccount()->getId() . "/" . $this->getAlbum()->getDirname();
        }
    }

    public function getAlbum() {
        return $this->album;
    }

    public function setAlbum($album) {
        $this->album = $album;

        return $this;
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
     * @return Photo
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

    /**
     * Set path
     *
     * @param string $path
     * @return Photo
     */
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null) {
        $this->file = $file;

        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * Add comments
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Comment\PhotoComment $comments
     * @return Photo
     */
    public function addComment(\Flash\Bundle\DefaultBundle\Entity\Comment\PhotoComment $comments) {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Comment\PhotoComment $comments
     */
    public function removeComment(\Flash\Bundle\DefaultBundle\Entity\Comment\PhotoComment $comments) {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments() {
        return $this->comments;
    }

    /**
     * Set avatar
     *
     * @param boolean $avatar
     * @return Photo
     */
    public function setAvatar($avatar) {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return boolean 
     */
    public function getAvatar() {
        return $this->avatar;
    }

    public function setGarbage($garbage) {
        $this->garbage = $garbage;

        return $this;
    }

    public function isAvatar() {
        return $this->avatar;
    }

    public function isGarbage() {
        return $this->garbage;
    }

}
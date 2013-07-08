<?php

namespace Flash\Bundle\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

/**
 * Account
 *
 * @ORM\Table(name="account")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Flash\Bundle\DefaultBundle\Repository\AccountRepository")
 * @ExclusionPolicy("all")
 */
class Account implements AdvancedUserInterface {

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
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     */
    protected $username;

    /**
     * @var string
     * @Type("string")
     * @ORM\Column(name="first_name", type="string", length=255)
     * @Assert\NotBlank(message = "Поле не может быть пустым")
     * @Assert\MinLength(limit=2, message = "Поле не может иметь менее 2 символов")
     * @Expose
     */
    protected $firstName;

    /**
     * @var string
     * @Type("string")
     * @ORM\Column(name="last_name", type="string", length=255)
     * @Assert\NotBlank(message = "Поле не может быть пустым")
     * @Assert\MinLength(limit=2, message = "Поле не может иметь менее 2 символов")
     * @Expose
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank(message = "Поле email не может быть пустым")
     * @Assert\Email(message = "Введите корректный email")
     */
    protected $email;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="Account")
     * @Expose
     */
    protected $roles;

    /**
     * @ORM\Column(name="city_id", type="integer")
     * @Assert\NotBlank(message = "Поле не city может быть пустым")
     */
    protected $city;

    /**
     * @ORM\Column(name="country_id", type="integer")
     * @Assert\NotBlank(message = "Поле country не может быть пустым")
     */
    protected $country;

    /**
     * @var date
     *
     * @ORM\Column(name="registered", type="datetime")
     */
    protected $dateRegist;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank(message = "Поле password не может быть пустым")
     */
    protected $password;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $about;

    /**
     * @ManyToOne(targetEntity="Group", inversedBy="accounts")
     */
    protected $group;

    /**
     * @ManyToOne(targetEntity="Photo", inversedBy="rating")
     */
    protected $photoLike;

    /**
     * @ManyToOne(targetEntity="\Flash\Bundle\DefaultBundle\Entity\Comment\PhotoComment", inversedBy="rating")
     */
    protected $photoCommentLike;

    /**
     * @OneToMany(targetEntity="\Flash\Bundle\DefaultBundle\Entity\Event", mappedBy="account")
     */
    protected $events;

    /**
     * @OneToMany(targetEntity="Comment", mappedBy="account")
     */
    protected $comments;

    /**
     * @OneToMany(targetEntity="\Flash\Bundle\DefaultBundle\Entity\Comment\PhotoComment", mappedBy="account")
     */
    protected $photoComments;

    /**
     * @Expose
     * @ORM\ManyToMany(targetEntity="\Flash\Bundle\DefaultBundle\Entity\Calendar\CalendarEvent", inversedBy="\Flash\Bundle\DefaultBundle\Entity\Account")
     */
    protected $calendarEvents;

    /**
     * @OneToMany(targetEntity="Photo", mappedBy="account")
     */
    protected $photos;
    
    /**
     * @OneToMany(targetEntity="Album", mappedBy="account")
     */
    protected $albums;

    /**
     * @OneToMany(targetEntity="CustomRole", mappedBy="account")
     */
    protected $customRoles;

    /**
     * @ManyToOne(targetEntity="Account", inversedBy="followers")
     * @Expose
     */
    protected $following;

    /**
     * @OneToMany(targetEntity="Account", mappedBy="following")
     * @Expose
     */
    protected $followers;
    
    
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_leader", type="boolean")
     * @Expose
     */
    protected $isLeader = false;

    /**
     * @OneToMany(targetEntity="Video", mappedBy="account")
     */
    protected $videos;

    public function __construct() {

        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
        $this->roles = new ArrayCollection();
        $this->customRoles = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->userEvents = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->photoComments = new ArrayCollection();
        $this->photoAlbums = new ArrayCollection();
        $this->calendarEvents = new ArrayCollection();
        $this->followers = new ArrayCollection();
    }

    public function equals(\Symfony\Component\Security\Core\User\UserInterface $user) {
        return $this->getEmail() === $user->getEmail();
    }

    public function __toString() {
        return "Account object:  " . $this->username . " " . $this->email;
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
     * @return Account
     */
    public function setUsername($name) {
        $this->username = $name;
        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Account
     */
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Account
     */
    public function setSalt($salt) {
        $this->salt = $salt;
        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Account
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Account
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive() {
        return $this->isActive;
    }

    /**
     * @return boolean
     */
    public function isAccountNonExpired() {
        return true;
    }

    /**
     * @return boolean
     */
    public function isAccountNonLocked() {
        return true;
    }

    /**
     * @return boolean
     */
    public function isCredentialsNonExpired() {
        return true;
    }

    /**
     * @return boolean
     */
    public function isEnabled() {
        return true;
    }

    /**
     * Add roles
     *
     * @param \Symfony\Component\Security\Core\Role\RoleInterface $role
     * @return Account
     */
    public function addRole(\Symfony\Component\Security\Core\Role\RoleInterface $role) {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Symfony\Component\Security\Core\Role\RoleInterface $role
     */
    public function removeRole(\Symfony\Component\Security\Core\Role\RoleInterface $role) {
        $this->roles->removeElement($role);
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles() {

        $roles = $this->roles->getValues();
        $roleNames = array();
        foreach ($roles as $role) {
            $rolesNames[] = $role->getRole();
        }

        if (NULL != $this->getCustomRoles()) {
            $cRoles = $this->customRoles->getValues();
            foreach ($cRoles as $r) {
                $rolesNames[] = $r->getRole();
            }
        }
        return $rolesNames;
    }

    public function eraseCredentials() {
        
    }

    public function getSalt() {
        return $this->salt;
    }

    public function getUsername() {
        return $this->username;
    }

    /**
     * Set about
     *
     * @param string $about
     * @return Account
     */
    public function setAbout($about) {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string 
     */
    public function getAbout() {
        return $this->about;
    }

    /**
     * Set group
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Group $group
     * @return Account
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
     * Add photos
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Photo $photos
     * @return Account
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
     * Add videos
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Video $videos
     * @return Account
     */
    public function addVideo(\Flash\Bundle\DefaultBundle\Entity\Video $videos) {
        $this->videos[] = $videos;

        return $this;
    }

    /**
     * Remove videos
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Video $videos
     */
    public function removeVideo(\Flash\Bundle\DefaultBundle\Entity\Video $videos) {
        $this->videos->removeElement($videos);
    }

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVideos() {
        return $this->videos;
    }

    /**
     * Set city
     *
     * @param integer $city
     * @return Account
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
     * @return Account
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
     * Add events
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Event $events
     * @return Account
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
     * Set dateRegist
     *
     * @param \DateTime $dateRegist
     * @return Account
     */
    public function setDateRegistration($dateRegist) {
        $this->dateRegist = $dateRegist;

        return $this;
    }

    /**
     * Get dateRegist
     *
     * @return \DateTime 
     */
    public function getDateRegistration() {
        return $this->dateRegist;
    }

    /**
     * Add userEvents
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\UserEvent $userEvents
     * @return Account
     */
    public function addUserEvent(\Flash\Bundle\DefaultBundle\Entity\UserEvent $userEvents) {
        $this->userEvents[] = $userEvents;

        return $this;
    }

    /**
     * Remove userEvents
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\UserEvent $userEvents
     */
    public function removeUserEvent(\Flash\Bundle\DefaultBundle\Entity\UserEvent $userEvents) {
        $this->userEvents->removeElement($userEvents);
    }

    /**
     * Get userEvents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserEvents() {
        return $this->userEvents;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Account
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Account
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * Add customRoles
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\CustomRole $customRoles
     * @return Account
     */
    public function addCustomRole(\Symfony\Component\Security\Core\Role\RoleInterface $customRoles) {
        $this->customRoles[] = $customRoles;

        return $this;
    }

    /**
     * Remove customRoles
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\CustomRole $customRoles
     */
    public function removeCustomRole(\Symfony\Component\Security\Core\Role\RoleInterface $customRoles) {
        $this->customRoles->removeElement($customRoles);
    }

    /**
     * Get customRoles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomRoles() {

        $rolesNames = null;
        $cRoles = $this->customRoles->getValues();
        foreach ($cRoles as $r) {
            $rolesNames[] = $r->getRole();
        }
        return $rolesNames;
    }

    /**
     * Add comments
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Comment $comments
     * @return Account
     */
    public function addComment(\Flash\Bundle\DefaultBundle\Entity\Comment $comments) {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Comment $comments
     */
    public function removeComment(\Flash\Bundle\DefaultBundle\Entity\Comment $comments) {
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
     * Add photoComments
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Comment\PhotoComment $photoComments
     * @return Account
     */
    public function addPhotoComment(\Flash\Bundle\DefaultBundle\Entity\Comment\PhotoComment $photoComments) {
        $this->photoComments[] = $photoComments;

        return $this;
    }

    /**
     * Remove photoComments
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Comment\PhotoComment $photoComments
     */
    public function removePhotoComment(\Flash\Bundle\DefaultBundle\Entity\Comment\PhotoComment $photoComments) {
        $this->photoComments->removeElement($photoComments);
    }

    /**
     * Get photoComments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhotoComments() {
        return $this->photoComments;
    }

    /**
     * Set photoLike
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Photo $photoLike
     * @return Account
     */
    public function setPhotoLike(\Flash\Bundle\DefaultBundle\Entity\Photo $photoLike = null) {
        $this->photoLike = $photoLike;

        return $this;
    }

    /**
     * Get photoLike
     *
     * @return \Flash\Bundle\DefaultBundle\Entity\Photo 
     */
    public function getPhotoLike() {
        return $this->photoLike;
    }

    public function removePhotoLike() {
        unset($this->photoLike);

        return $this;
    }

    /**
     * Set dateRegist
     *
     * @param \DateTime $dateRegist
     * @return Account
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
     * Set photoCommentLike
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Comment\PhotoComment $photoCommentLike
     * @return Account
     */
    public function setPhotoCommentLike(\Flash\Bundle\DefaultBundle\Entity\Comment\PhotoComment $photoCommentLike = null) {
        $this->photoCommentLike = $photoCommentLike;

        return $this;
    }

    public function removePhotoCommentLike() {
        unset($this->photoCommentLike);

        return $this;
    }

    /**
     * Get photoCommentLike
     *
     * @return \Flash\Bundle\DefaultBundle\Entity\Comment\PhotoComment 
     */
    public function getPhotoCommentLike() {
        return $this->photoCommentLike;
    }

    /**
     * Add calendarEvents
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Calendar\CalendarEvent $calendarEvents
     * @return Account
     */
    public function addCalendarEvent(\Flash\Bundle\DefaultBundle\Entity\Calendar\CalendarEvent $calendarEvents) {
        $this->calendarEvents[] = $calendarEvents;

        return $this;
    }

    /**
     * Remove calendarEvents
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\Calendar\CalendarEvent $calendarEvents
     */
    public function removeCalendarEvent(\Flash\Bundle\DefaultBundle\Entity\Calendar\CalendarEvent $calendarEvents) {
        $this->calendarEvents->removeElement($calendarEvents);
    }

    /**
     * Get calendarEvents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCalendarEvents() {
        return $this->calendarEvents;
    }


    /**
     * Set following
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface $following
     * @return Account
     */
    public function setFollowing(\Symfony\Component\Security\Core\User\UserInterface $following = null)
    {
        $this->following = $following;
    
        return $this;
    }

    /**
     * Get following
     *
     * @return \Flash\Bundle\DefaultBundle\Entity\Account 
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * Add followers
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface $follower
     * @return Account
     */
    public function addFollower(\Symfony\Component\Security\Core\User\UserInterface $follower)
    {
        $this->followers[] = $follower;
    
        return $this;
    }

    /**
     * Remove followers
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface $follower
     */
    public function removeFollower(\Symfony\Component\Security\Core\User\UserInterface $follower)
    {
        $this->followers->removeElement($follower);
    }

    /**
     * Get followers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * Set isLeader
     *
     * @param boolean $isLeader
     * @return Account
     */
    public function setIsLeader($isLeader)
    {
        $this->isLeader = $isLeader;
    
        return $this;
    }

    /**
     * Get isLeader
     *
     * @return boolean 
     */
    public function getIsLeader()
    {
        return $this->isLeader;
    }

    /**
     * Add photoAlbums
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\PhotoAlbum $photoAlbums
     * @return Account
     */
    public function addAlbum(\Flash\Bundle\DefaultBundle\Entity\Album $album)
    {
        $this->albums[] = $album;
    
        return $this;
    }

    /**
     * Remove photoAlbums
     *
     * @param \Flash\Bundle\DefaultBundle\Entity\PhotoAlbum 
     */
    public function removeAlbum(\Flash\Bundle\DefaultBundle\Entity\Album $album)
    {
        $this->albums->removeElement($album);
    }

    /**
     * Get photoAlbums
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAlbums()
    {
        return $this->albums;
    }
}
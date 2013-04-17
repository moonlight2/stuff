<?php

namespace Flash\Bundle\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Account
 *
 * @ORM\Table(name="account")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Flash\Bundle\DefaultBundle\Repository\AccountRepository")
 */
class Account implements AdvancedUserInterface {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $salt;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="Account")
     */
    private $roles;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="text")
     */
    private $about;

    /**
     *
     * @ManyToOne(targetEntity="Group", inversedBy="accounts")
     */
    private $group;

    /**
     * @OneToMany(targetEntity="Photo", mappedBy="account")
     */
    private $photos;
    
    /**
     * @OneToMany(targetEntity="Video", mappedBy="account")
     */
    private $videos;

    public function __construct($email) {

        $this->email = $email;
        $this->username = $email;
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
        $this->roles = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->videos = new ArrayCollection();
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
     * @param \Flash\Bundle\DemoBundle\Entity\Role $roles
     * @return Account
     */
    public function addRole(\Flash\Bundle\DefaultBundle\Entity\Role $role) {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Flash\Bundle\DemoBundle\Entity\Role $roles
     */
    public function removeRole(\Flash\Bundle\DefaultBundle\Entity\Role $role) {
        $this->roles->removeElement($role);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoles() {
        $rolesArray = $this->roles->getValues();
        foreach ($rolesArray as $role) {
            $rolesNames[] = $role->getName();
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

}
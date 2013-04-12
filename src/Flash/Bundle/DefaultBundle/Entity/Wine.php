<?php

namespace Flash\Bundle\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wine
 *
 * @ORM\Table(name="wine")
 * @ORM\Entity
 */
class Wine 
{

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
     * @ORM\Column(name="name", type="string", length=45)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="grapes", type="string", length=45)
     */
    private $grapes;
    
    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=45)
     */
    private $country;
    
    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=45)
     */
    private $region;
    
    /**
     * @var string
     *
     * @ORM\Column(name="year", type="string", length=45)
     */
    private $year;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="blob")
     */
    private $description;
    
    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=256)
     */
    private $picture;

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
     * @return Wine
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
     * Set grapes
     *
     * @param string $grapes
     * @return Wine
     */
    public function setGrapes($grapes)
    {
        $this->grapes = $grapes;
    
        return $this;
    }

    /**
     * Get grapes
     *
     * @return string 
     */
    public function getGrapes()
    {
        return $this->grapes;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Wine
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set region
     *
     * @param string $region
     * @return Wine
     */
    public function setRegion($region)
    {
        $this->region = $region;
    
        return $this;
    }

    /**
     * Get region
     *
     * @return string 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set year
     *
     * @param integer $year
     * @return Wine
     */
    public function setYear($year)
    {
        $this->year = $year;
    
        return $this;
    }

    /**
     * Get year
     *
     * @return integer 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Wine
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return 'Desc';
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return Wine
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    
        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }
}
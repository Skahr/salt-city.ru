<?php

namespace Skahr\SaltCityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reset
 */
class Reset
{
    /**
     * @var integer
     */
    private $userid;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var \DateTime
     */
    private $datecr;


    /**
     * Set userid
     *
     * @param integer $userid
     * @return Reset
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return integer 
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Reset
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set datecr
     *
     * @param \DateTime $datecr
     * @return Reset
     */
    public function setDatecr($datecr)
    {
        $this->datecr = $datecr;

        return $this;
    }

    /**
     * Get datecr
     *
     * @return \DateTime 
     */
    public function getDatecr()
    {
        return $this->datecr;
    }
    /**
     * @ORM\PrePersist
     */
    public function setDatecrValue()
    {
        $this->datecr = new \DateTime();
    }
}

<?php

namespace Skahr\SaltCityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 */
class Comment
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $usermessage;

    /**
     * @var integer
     */
    private $status;

    /**
     * @var string
     */
    private $adminreply;

    /**
     * @var \DateTime
     */
    private $datecr;


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
     * Set username
     *
     * @param string $username
     * @return Comment
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set usermessage
     *
     * @param string $usermessage
     * @return Comment
     */
    public function setUsermessage($usermessage)
    {
        $this->usermessage = $usermessage;

        return $this;
    }

    /**
     * Get usermessage
     *
     * @return string 
     */
    public function getUsermessage()
    {
        return $this->usermessage;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Comment
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set adminreply
     *
     * @param string $adminreply
     * @return Comment
     */
    public function setAdminreply($adminreply)
    {
        $this->adminreply = $adminreply;

        return $this;
    }

    /**
     * Get adminreply
     *
     * @return string 
     */
    public function getAdminreply()
    {
        return $this->adminreply;
    }

    /**
     * Set datecr
     *
     * @param \DateTime $datecr
     * @return Comment
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

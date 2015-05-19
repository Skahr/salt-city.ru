<?php

namespace Skahr\SaltCityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sale
 */
class Sale
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $salestext;

    /**
     * @var integer
     */
    private $status;

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
     * Set salestext
     *
     * @param string $salestext
     * @return Sale
     */
    public function setSalestext($salestext)
    {
        $this->salestext = $salestext;

        return $this;
    }

    /**
     * Get salestext
     *
     * @return string 
     */
    public function getSalestext()
    {
        return $this->salestext;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Sale
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
     * Set datecr
     *
     * @param \DateTime $datecr
     * @return Sale
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

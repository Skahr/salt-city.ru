<?php

namespace Skahr\SaltCityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * News
 */
class News
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $newstext;

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
     * Set newstext
     *
     * @param string $newstext
     * @return News
     */
    public function setNewstext($newstext)
    {
        $this->newstext = $newstext;

        return $this;
    }

    /**
     * Get newstext
     *
     * @return string 
     */
    public function getNewstext()
    {
        return $this->newstext;
    }

    /**
     * Set datecr
     *
     * @param \DateTime $datecr
     * @return News
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

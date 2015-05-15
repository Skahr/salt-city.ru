<?php

namespace Skahr\SaltCityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Price
 */
class Price
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $pricename;

    /**
     * @var integer
     */
    private $price;

    /**
     * @var string
     */
    private $priceinfo;


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
     * Set pricename
     *
     * @param string $pricename
     * @return Price
     */
    public function setPricename($pricename)
    {
        $this->pricename = $pricename;

        return $this;
    }

    /**
     * Get pricename
     *
     * @return string 
     */
    public function getPricename()
    {
        return $this->pricename;
    }

    /**
     * Set price
     *
     * @param integer $price
     * @return Price
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set priceinfo
     *
     * @param string $priceinfo
     * @return Price
     */
    public function setPriceinfo($priceinfo)
    {
        $this->priceinfo = $priceinfo;

        return $this;
    }

    /**
     * Get priceinfo
     *
     * @return string 
     */
    public function getPriceinfo()
    {
        return $this->priceinfo;
    }
}

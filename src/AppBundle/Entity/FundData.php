<?php

namespace AppBundle\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * FundData.
 *
 * @ORM\Table(name="fund_data")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FundDataRepository")
 */
class FundData
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var DateTimeInterface
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var Fund
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Fund", inversedBy="fundData")
     * @ORM\JoinColumn(name="fund_id", referencedColumnName="id")
     */
    private $fund;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param DateTimeInterface $date
     *
     * @return FundData
     */
    public function setDate(DateTimeInterface $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param float $price
     *
     * @return FundData
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return Fund
     */
    public function getFund()
    {
        return $this->fund;
    }

    /**
     * @param Fund $fund
     *
     * @return FundData
     */
    public function setFund(Fund $fund)
    {
        $this->fund = $fund;

        return $this;
    }
}

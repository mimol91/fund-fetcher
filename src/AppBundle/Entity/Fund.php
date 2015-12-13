<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="fund")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FundRepository")
 */
class Fund
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="externalId", type="integer", unique=true)
     */
    private $externalId;

    /**
     * @var FundData[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FundData", mappedBy="fund")
     */
    private $fundData;

    public function __construct()
    {
        $this->fundData = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     *
     * @return Fund
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $externalId
     *
     * @return Fund
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * @return int
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param FundData $fundData
     *
     * @return Fund
     */
    public function addFundData(FundData $fundData)
    {
        $this->fundData->add($fundData);

        return $this;
    }

    /**
     * @param FundData $fundData
     */
    public function removeFundDat(FundData $fundData)
    {
        $this->fundData->removeElement($fundData);
    }

    /**
     * @return ArrayCollection
     */
    public function getFundData()
    {
        return $this->fundData;
    }
}

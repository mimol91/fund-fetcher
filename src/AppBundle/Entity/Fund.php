<?php

namespace AppBundle\Entity;

use AppBundle\Doctrine\FundDataCollection;
use AppBundle\Service\ScoreCalculator\FundScoreCalculatorInterface;
use AppBundle\Service\ScoreCalculator\ScoreCalculatorException;
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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", unique=true)
     */
    private $externalId;

    /**
     * @var null|float
     */
    private $score = null;

    /**
     * @var FundDataCollection
     *
     * @ORM\Column(type="fund_data_collection")
     */
    private $fundDataCollection;

    public function __construct()
    {
        $this->fundDataCollection = new FundDataCollection();
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
     * @param FundDataCollection $fundDataCollection
     *
     * @return Fund
     */
    public function setFundData(FundDataCollection $fundDataCollection)
    {
        $this->fundDataCollection = $fundDataCollection;

        return $this;
    }

    /**
     * @return FundDataCollection
     */
    public function getFundDataCollection()
    {
        return $this->fundDataCollection;
    }

    /**
     * @param FundScoreCalculatorInterface $scoreCalculator
     *
     * @return float|null
     */
    public function generateScore(FundScoreCalculatorInterface $scoreCalculator)
    {
        try {
            return $this->score = $scoreCalculator->getScore($this);
        } catch (ScoreCalculatorException $e) {
            return $this->score = null;
        }
    }

    /**
     * @return float|null
     */
    public function getScore()
    {
        return $this->score;
    }
}

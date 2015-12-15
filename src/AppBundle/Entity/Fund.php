<?php

namespace AppBundle\Entity;

use AppBundle\Doctrine\FundDataCollection;
use AppBundle\Service\ScoreCalculator\FundScoreCalculatorInterface;
use AppBundle\Service\ScoreCalculator\ScoreCalculatorException;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

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
     * @Groups({"list", "fund_details"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Groups({"list", "fund_details"})
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", unique=true)
     * @Groups({"list", "fund_details"})
     */
    private $externalId;

    /**
     * @var array
     * @Groups({"list", "fund_details"})
     */
    private $score = [];

    /**
     * @var FundDataCollection
     *
     * @ORM\Column(type="fund_data_collection")
     * @Groups({"fund_details"})
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
     */
    public function generateScore(FundScoreCalculatorInterface $scoreCalculator)
    {
        try {
            $this->score[$scoreCalculator->getCalculatorName()] = $scoreCalculator->getScore($this);
        } catch (ScoreCalculatorException $e) {
            $this->score[$scoreCalculator->getCalculatorName()] = null;
        }
    }

    /**
     * @param string $scoreName
     *
     * @return float|null
     */
    public function getScoreValue($scoreName)
    {
        if (!array_key_exists($scoreName, $this->score)) {
            return;
        }

        return $this->score[$scoreName];
    }

    /**
     * @return array
     */
    public function getScore()
    {
        return $this->score;
    }
}

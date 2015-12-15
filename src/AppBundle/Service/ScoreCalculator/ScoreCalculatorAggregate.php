<?php

namespace AppBundle\Service\ScoreCalculator;

use AppBundle\Entity\Fund;
use Doctrine\Common\Collections\ArrayCollection;
use InvalidArgumentException;

class ScoreCalculatorAggregate
{
    /** @var ArrayCollection */
    protected $scoreCalculators;

    public function __construct()
    {
        $this->scoreCalculators = new ArrayCollection();
    }

    /**
     * @param string $scoreCalculatorName
     *
     * @return FundScoreCalculatorInterface
     *
     * @throws InvalidArgumentException
     */
    public function getScoreCalculator($scoreCalculatorName)
    {
        if (!$this->scoreCalculators->containsKey($scoreCalculatorName)) {
            throw new InvalidArgumentException(sprintf('Unable to find score calculator with id %s', $scoreCalculatorName));
        }

        return $this->scoreCalculators->get($scoreCalculatorName);
    }

    /**
     * @param FundScoreCalculatorInterface $scoreCalculator
     */
    public function registerScoreCalculator(FundScoreCalculatorInterface $scoreCalculator)
    {
        if ($this->scoreCalculators->contains($scoreCalculator)) {
            return;
        }

        $this->scoreCalculators->set($scoreCalculator->getCalculatorName(), $scoreCalculator);
    }

    /**
     * @param Fund[] $funds
     */
    public function calculateScore(array $funds)
    {
        foreach ($funds as $fund) {
            foreach ($this->scoreCalculators as $scoreCalculator) {
                $fund->generateScore($scoreCalculator);
            }
        }
    }
}

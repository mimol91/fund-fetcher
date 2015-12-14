<?php

namespace AppBundle\Service\Sorter;

use AppBundle\Entity\Fund;
use AppBundle\Service\ScoreCalculator\FundScoreCalculatorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

class Sorter
{
    /**
     * @param Fund[]                       $funds
     * @param FundScoreCalculatorInterface $scoreCalculator
     *
     * @return Fund[]
     */
    public function getSortedByScore(array $funds, FundScoreCalculatorInterface $scoreCalculator)
    {
        $funds = new ArrayCollection($funds);
        foreach ($funds as $fund) {
            $fund->generateScore($scoreCalculator);
        }

        $criteria = Criteria::create()
            ->orderBy(['score' => Criteria::DESC]);

        return $funds->matching($criteria)->toArray();
    }
}

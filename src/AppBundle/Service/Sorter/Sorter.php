<?php

namespace AppBundle\Service\Sorter;

use AppBundle\Entity\Fund;

class Sorter
{
    /**
     * @param Fund[] $funds
     * @param string $scoreCalculatorName
     *
     * @return Fund[]
     */
    public function getSortedByScore(array $funds, $scoreCalculatorName)
    {
        usort($funds, function (Fund $a, Fund $b) use ($scoreCalculatorName) {
            $aScore = $a->getScoreValue($scoreCalculatorName);
            $bScore = $b->getScoreValue($scoreCalculatorName);

            return $aScore < $bScore;
        });

        return $funds;
    }
}

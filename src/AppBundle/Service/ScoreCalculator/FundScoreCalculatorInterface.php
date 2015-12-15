<?php

namespace AppBundle\Service\ScoreCalculator;

use AppBundle\Entity\Fund;

interface FundScoreCalculatorInterface
{
    /**
     * @param Fund $fund
     *
     * @return float
     *
     * @throws ScoreCalculatorException
     */
    public function getScore(Fund $fund);

    /**
     * @return string
     */
    public function getCalculatorName();
}

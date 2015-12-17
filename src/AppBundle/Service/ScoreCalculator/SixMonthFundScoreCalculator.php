<?php

namespace AppBundle\Service\ScoreCalculator;

use Carbon\CarbonInterval;

class SixMonthFundScoreCalculator extends AbstractMonthCalculator implements FundScoreCalculatorInterface
{
    const NAME = 'six_month';

    /**
     * {@inheritdoc}
     */
    protected function getStartInterval()
    {
        return CarbonInterval::months(6);
    }

    /**
     * {@inheritdoc}
     */
    public function getCalculatorName()
    {
        return self::NAME;
    }
}

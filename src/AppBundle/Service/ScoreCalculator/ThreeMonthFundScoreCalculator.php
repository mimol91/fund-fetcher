<?php

namespace AppBundle\Service\ScoreCalculator;

use Carbon\CarbonInterval;

class ThreeMonthFundScoreCalculator extends AbstractMonthCalculator implements FundScoreCalculatorInterface
{
    const NAME = 'three_month';
    /**
     * {@inheritdoc}
     */
    protected function getStartInterval()
    {
        return CarbonInterval::months(3);
    }

    /**
     * {@inheritdoc}
     */
    public function getCalculatorName()
    {
        return self::NAME;
    }
}

<?php

namespace AppBundle\Service\ScoreCalculator;

use Carbon\CarbonInterval;

class OneWeekFundScoreCalculator extends AbstractMonthCalculator implements FundScoreCalculatorInterface
{
    const NAME = 'one_week';

    /**
     * {@inheritdoc}
     */
    protected function getStartInterval()
    {
        return CarbonInterval::week();
    }

    /**
     * {@inheritdoc}
     */
    public function getCalculatorName()
    {
        return self::NAME;
    }
}

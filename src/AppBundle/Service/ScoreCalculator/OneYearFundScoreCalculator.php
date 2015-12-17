<?php

namespace AppBundle\Service\ScoreCalculator;

use Carbon\CarbonInterval;

class OneYearFundScoreCalculator extends AbstractMonthCalculator implements FundScoreCalculatorInterface
{
    const NAME = 'one_year';

    /**
     * {@inheritdoc}
     */
    protected function getStartInterval()
    {
        return CarbonInterval::year();
    }

    /**
     * {@inheritdoc}
     */
    public function getCalculatorName()
    {
        return self::NAME;
    }
}

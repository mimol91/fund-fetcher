<?php

namespace AppBundle\Service\ScoreCalculator;

use Carbon\CarbonInterval;

class OneMonthFundScoreCalculator extends AbstractMonthCalculator implements FundScoreCalculatorInterface
{
    const NAME = 'one_month';

    /**
     * {@inheritdoc}
     */
    protected function getStartInterval()
    {
        return CarbonInterval::month();
    }

    /**
     * {@inheritdoc}
     */
    public function getCalculatorName()
    {
        return self::NAME;
    }
}

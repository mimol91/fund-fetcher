<?php

namespace AppBundle\Service\ScoreCalculator;

use Carbon\Carbon;

class SixMonthFundScoreCalculator extends AbstractMonthCalculator implements FundScoreCalculatorInterface
{
    const NAME = 'six_month';

    /**
     * {@inheritdoc}
     */
    protected function getStartDate()
    {
        return Carbon::today()->subMonth(6);
    }

    /**
     * {@inheritdoc}
     */
    public function getCalculatorName()
    {
        return self::NAME;
    }
}

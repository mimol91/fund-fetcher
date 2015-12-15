<?php

namespace AppBundle\Service\ScoreCalculator;

use Carbon\Carbon;

class ThreeMonthFundScoreCalculator extends AbstractMonthCalculator implements FundScoreCalculatorInterface
{
    const NAME = 'three_month';
    /**
     * {@inheritdoc}
     */
    protected function getStartDate()
    {
        return Carbon::today()->subMonth(3);
    }

    /**
     * {@inheritdoc}
     */
    public function getCalculatorName()
    {
        return self::NAME;
    }
}

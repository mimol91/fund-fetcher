<?php

namespace AppBundle\Service\ScoreCalculator;

use Carbon\Carbon;

class OneMonthFundScoreCalculator extends AbstractMonthCalculator implements FundScoreCalculatorInterface
{
    const NAME = 'one_month';

    /**
     * {@inheritdoc}
     */
    protected function getStartDate()
    {
        return Carbon::today()->subMonth(1);
    }

    /**
     * {@inheritdoc}
     */
    public function getCalculatorName()
    {
        return self::NAME;
    }
}

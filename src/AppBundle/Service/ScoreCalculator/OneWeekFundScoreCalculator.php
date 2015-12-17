<?php

namespace AppBundle\Service\ScoreCalculator;

use Carbon\Carbon;

class OneWeekFundScoreCalculator extends AbstractMonthCalculator implements FundScoreCalculatorInterface
{
    const NAME = 'one_week';

    /**
     * {@inheritdoc}
     */
    protected function getStartDate()
    {
        return Carbon::today()->subWeek();
    }

    /**
     * {@inheritdoc}
     */
    public function getCalculatorName()
    {
        return self::NAME;
    }
}

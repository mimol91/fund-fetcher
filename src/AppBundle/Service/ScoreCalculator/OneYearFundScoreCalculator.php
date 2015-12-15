<?php

namespace AppBundle\Service\ScoreCalculator;

use Carbon\Carbon;

class OneYearFundScoreCalculator extends AbstractMonthCalculator implements FundScoreCalculatorInterface
{
    const NAME = 'one_year';

    /**
     * {@inheritdoc}
     */
    protected function getStartDate()
    {
        return Carbon::today()->subYear();
    }

    /**
     * {@inheritdoc}
     */
    public function getCalculatorName()
    {
        return self::NAME;
    }
}

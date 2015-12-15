<?php

namespace AppBundle\Service\ScoreCalculator;

use AppBundle\Doctrine\FundDataCollection;
use AppBundle\Entity\Fund;
use AppBundle\Model\FundData;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DateTimeInterface;
use Doctrine\Common\Collections\Criteria;

abstract class AbstractMonthCalculator
{
    /**
     * @param Fund $fund
     *
     * @return float
     *
     * @throws ScoreCalculatorException
     */
    public function getScore(Fund $fund)
    {
        $fundDataCollection = $fund->getFundDataCollection();

        $startValue = $this->getStartValue($fundDataCollection);
        $endValue = $this->getEndValue($fundDataCollection);

        $gain = 100 - ($endValue * 100 / $startValue);

        return $gain;
    }

    /**
     * @return DateTimeInterface
     */
    abstract protected function getStartDate();

    /**
     * @param FundDataCollection $fundDataCollection
     *
     * @return float
     *
     * @throws ScoreCalculatorException
     */
    private function getStartValue(FundDataCollection $fundDataCollection)
    {
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->gte('date', $this->getStartDate()))
            ->setMaxResults(1);

        $recentData = $fundDataCollection->matching($criteria)->first();

        if (!$recentData instanceof FundData) {
            throw new ScoreCalculatorException('Unable to determine start value.');
        }

        return $recentData->getPrice();
    }

    /**
     * @param FundDataCollection $fundDataCollection
     *
     * @return float
     *
     * @throws ScoreCalculatorException
     */
    private function getEndValue(FundDataCollection $fundDataCollection)
    {
        $criteria = Criteria::create()
            ->orderBy(['date' => Criteria::DESC])
            ->setMaxResults(1);

        $recentData = $fundDataCollection->matching($criteria)->first();
        if (!$recentData instanceof FundData) {
            throw new ScoreCalculatorException('Unable to determine recent value.');
        }

        if (Carbon::today()->sub($this->getMaxIntervalMismatch()) > $recentData->getDate()) {
            throw new ScoreCalculatorException(sprintf(
                'Unable to determine recent value. Last update was on %s',
                $recentData->getDate()->format('Y-m-d')
            ));
        }

        return $recentData->getPrice();
    }

    /**
     * @return CarbonInterval
     */
    private function getMaxIntervalMismatch()
    {
        return CarbonInterval::days(7);
    }
}

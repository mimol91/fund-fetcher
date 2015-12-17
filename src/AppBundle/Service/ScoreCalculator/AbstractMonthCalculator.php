<?php

namespace AppBundle\Service\ScoreCalculator;

use AppBundle\Doctrine\FundDataCollection;
use AppBundle\Entity\Fund;
use AppBundle\Model\FundData;
use Carbon\Carbon;
use Carbon\CarbonInterval;
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

        $endValue = $this->getEndValue($fundDataCollection);
        $startValue = $this->getStartValue($fundDataCollection, $endValue);

        $gain = -100 + $endValue->getPrice() * 100 / $startValue->getPrice();

        return $gain;
    }

    /**
     * @return CarbonInterval
     */
    abstract protected function getStartInterval();

    /**
     * @param FundDataCollection $fundDataCollection
     *
     * @return FundData $endValue
     *
     * @throws ScoreCalculatorException
     */
    private function getStartValue(FundDataCollection $fundDataCollection, FundData $endValue)
    {

        $startDate = (new Carbon())->setTimestamp($endValue->getDate()->getTimestamp());
        $startDate->sub($this->getStartInterval());
        
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->gte('date', $startDate))
            ->setMaxResults(1);

        $recentData = $fundDataCollection->matching($criteria)->first();

        if (!$recentData instanceof FundData) {
            throw new ScoreCalculatorException('Unable to determine start value.');
        }

        return $recentData;
    }

    /**
     * @param FundDataCollection $fundDataCollection
     *
     * @return FundData
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

        return $recentData;
    }

    /**
     * @return CarbonInterval
     */
    private function getMaxIntervalMismatch()
    {
        return CarbonInterval::days(7);
    }
}

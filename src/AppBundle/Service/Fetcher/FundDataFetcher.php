<?php


namespace AppBundle\Service\Fetcher;

use AppBundle\Entity\Fund;
use Doctrine\Common\Collections\ArrayCollection;

class FundDataFetcher
{
    /**
     * @param Fund $fund
     * @return ArrayCollection
     */
    public function fetchData(Fund $fund)
    {
        $fundData = new ArrayCollection();

        return $fundData;
    }
}

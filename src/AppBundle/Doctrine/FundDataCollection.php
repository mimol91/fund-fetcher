<?php

namespace AppBundle\Doctrine;

use AppBundle\Model\FundData;
use Doctrine\Common\Collections\Criteria;

class FundDataCollection extends AbstractSerializableCollection
{
    /**
     * @return ArraySerializableInterface
     */
    public function getElementClass()
    {
        return FundData::class;
    }

    /**
     * @return FundDataCollection
     */
    public function getSortedByDate()
    {
        $criteria = Criteria::create()
            ->orderBy(['date' => Criteria::ASC, 'name' => Criteria::ASC]);

        return $this->matching($criteria);
    }
}

<?php

namespace AppBundle\Doctrine;

use AppBundle\Model\FundData;

class FundDataCollection extends AbstractSerializableCollection
{
    /**
     * @return ArraySerializableInterface
     */
    public function getElementClass()
    {
        return FundData::class;
    }
}

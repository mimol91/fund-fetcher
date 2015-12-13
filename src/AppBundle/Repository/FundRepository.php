<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class FundRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getExternalFundIds()
    {
        $qb = $this->createQueryBuilder('f');

        return array_column(
            $qb
                ->select('f.externalId')
                ->getQuery()
                ->getArrayResult(),
            'externalId'
        );
    }
}
